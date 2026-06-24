<h2>NexaDesk Ticket Reply</h2>

<p>Halo {{ $ticket->email_from_name ?: 'User' }},</p>

<p>Tim helpdesk telah membalas ticket Anda:</p>

<div style="padding: 12px; border-left: 4px solid #4f46e5; background: #f8fafc;">
    {!! nl2br(e($replyMessage)) !!}
</div>

<hr>

<p><strong>Ticket:</strong> #{{ $ticket->id }} - {{ $ticket->title }}</p>
<p><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $ticket->status)) }}</p>

<p>Terima kasih,<br>NexaDesk Support</p>