<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketEmailReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $replyMessage
    ) {}

    public function build()
    {
        return $this
            ->subject('Re: [NexaDesk #' . $this->ticket->id . '] ' . ($this->ticket->email_subject ?? $this->ticket->title))
            ->view('emails.ticket-reply');
    }
}