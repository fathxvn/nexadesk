<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketEmailReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $replyMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replySubject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-email-reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function replySubject(): string
    {
        $originalSubject = $this->ticket->email_subject ?: $this->ticket->title;

        return "Re: [NexaDesk #{$this->ticket->id}] {$originalSubject}";
    }
}
