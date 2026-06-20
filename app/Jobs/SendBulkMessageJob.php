<?php

namespace App\Jobs;

use App\Mail\ContactEmail;
use App\Models\BulkSend;
use App\Models\Contact;
use App\Models\EmailMessage;
use App\Models\Message;
use App\Services\TwilioClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendBulkMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public BulkSend $bulkSend,
        public Contact $contact,
    ) {}

    public function middleware(): array
    {
        // Rate-limit per channel — defined in AppServiceProvider.
        return [new RateLimited('bulk-'.$this->bulkSend->channel)];
    }

    public function handle(TwilioClient $twilio): void
    {
        $body = $this->renderTokens($this->bulkSend->body);
        $subject = $this->bulkSend->subject
            ? $this->renderTokens($this->bulkSend->subject)
            : null;

        try {
            match ($this->bulkSend->channel) {
                'sms', 'whatsapp' => $this->sendSms($twilio, $body),
                'email' => $this->sendEmail($subject, $body),
            };

            $this->incrementCounter('sent_count');
            $this->contact->forceFill(['last_contacted_at' => now()])->save();
        } catch (\Throwable $e) {
            Log::warning('Bulk send failed', [
                'bulk_send_id' => $this->bulkSend->id,
                'contact_id' => $this->contact->id,
                'channel' => $this->bulkSend->channel,
                'error' => $e->getMessage(),
            ]);
            $this->incrementCounter('failed_count');
            // Don't rethrow — we want the batch to keep going even when one contact fails.
        }

        $this->maybeMarkFinished();
    }

    protected function sendSms(TwilioClient $twilio, string $body): void
    {
        if (! $this->contact->phone) {
            throw new \RuntimeException('Contact has no phone number');
        }

        $result = $twilio->sendSms($this->contact->phone, $body, $this->bulkSend->channel);

        Message::create([
            'team_id' => $this->contact->team_id,
            'contact_id' => $this->contact->id,
            'user_id' => $this->bulkSend->user_id,
            'channel' => $this->bulkSend->channel,
            'direction' => 'outbound',
            'status' => $result['status'],
            'from_number' => $twilio->from() ?? 'fake-system',
            'to_number' => $this->contact->phone,
            'body' => $body,
            'twilio_sid' => $result['sid'],
            'twilio_payload' => $result['payload'],
            'sent_at' => now(),
        ]);

        // TwilioClient returns failed status instead of throwing — surface that
        // up to the outer try/catch so this contact gets counted as a failure.
        if ($result['status'] === 'failed') {
            throw new \RuntimeException($result['error'] ?? 'Twilio send failed');
        }
    }

    protected function sendEmail(?string $subject, string $body): void
    {
        if (! $this->contact->email) {
            throw new \RuntimeException('Contact has no email');
        }

        $email = EmailMessage::create([
            'team_id' => $this->contact->team_id,
            'contact_id' => $this->contact->id,
            'user_id' => $this->bulkSend->user_id,
            'from_email' => config('mail.from.address', 'noreply@laracontact.test'),
            'to_email' => $this->contact->email,
            'subject' => $subject ?? '(no subject)',
            'body_text' => $body,
            'tracking_id' => Str::uuid()->toString(),
            'status' => 'queued',
        ]);

        Mail::to($this->contact->email)->send(new ContactEmail($email));

        $email->forceFill(['status' => 'sent', 'sent_at' => now()])->save();
    }

    protected function renderTokens(string $template): string
    {
        $map = [
            '{{first_name}}' => Str::before($this->contact->name, ' '),
            '{{name}}'       => $this->contact->name,
            '{{company}}'    => $this->contact->company ?? '',
            '{{email}}'      => $this->contact->email ?? '',
            '{{phone}}'      => $this->contact->phone ?? '',
        ];

        return strtr($template, $map);
    }

    protected function incrementCounter(string $column): void
    {
        DB::table('bulk_sends')
            ->where('id', $this->bulkSend->id)
            ->increment($column);
    }

    protected function maybeMarkFinished(): void
    {
        $fresh = $this->bulkSend->fresh();
        if (! $fresh) return;

        if (($fresh->sent_count + $fresh->failed_count) >= $fresh->total_count && ! $fresh->finished_at) {
            $fresh->forceFill(['finished_at' => now()])->save();
        }
    }
}
