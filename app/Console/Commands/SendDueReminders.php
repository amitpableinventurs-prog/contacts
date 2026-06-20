<?php

namespace App\Console\Commands;

use App\Mail\ReminderDueMail;
use App\Models\Message;
use App\Models\Reminder;
use App\Services\TwilioClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDueReminders extends Command
{
    protected $signature = 'reminders:send-due';
    protected $description = 'Send notifications for reminders whose remind_at has passed';

    public function handle(TwilioClient $twilio): int
    {
        $due = Reminder::withoutTeamScope()
            ->where('status', 'pending')
            ->whereNull('notified_at')
            ->where('remind_at', '<=', now())
            ->with(['user', 'contact'])
            ->get();

        foreach ($due as $reminder) {
            if ($reminder->notify_email && $reminder->user->email) {
                Mail::to($reminder->user->email)->queue(new ReminderDueMail($reminder));
            }

            if ($reminder->notify_sms && $reminder->user->phone) {
                $result = $twilio->sendSms($reminder->user->phone, "Reminder: {$reminder->title} — due now.");
                Message::withoutEvents(fn () => Message::create([
                    'team_id' => $reminder->team_id,
                    'contact_id' => $reminder->contact_id,
                    'user_id' => $reminder->user_id,
                    'channel' => 'sms',
                    'direction' => 'outbound',
                    'status' => $result['status'],
                    'from_number' => $twilio->from() ?? 'system',
                    'to_number' => $reminder->user->phone,
                    'body' => "Reminder: {$reminder->title}",
                    'twilio_sid' => $result['sid'],
                    'sent_at' => now(),
                ]));
            }

            $reminder->forceFill(['notified_at' => now()])->save();
            $this->info("Notified reminder #{$reminder->id}: {$reminder->title}");
        }

        $this->info(count($due).' reminder(s) processed.');
        return self::SUCCESS;
    }
}
