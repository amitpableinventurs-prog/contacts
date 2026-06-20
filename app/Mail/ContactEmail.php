<?php

namespace App\Mail;

use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public EmailMessage $emailMessage)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->emailMessage->from_email, config('mail.from.name')),
            to: [new Address($this->emailMessage->to_email)],
            subject: $this->emailMessage->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'body' => $this->emailMessage->body_html ?: nl2br(e($this->emailMessage->body_text ?? '')),
                'trackingId' => $this->emailMessage->tracking_id,
            ],
        );
    }
}
