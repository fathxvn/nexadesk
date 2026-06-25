<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Received #{{ $ticket->id }}</title>
</head>
<body style="margin: 0; background-color: #f8fafc; color: #334155; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 640px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;">
                    <tr>
                        <td style="background-color: #4f46e5; padding: 24px 28px; color: #ffffff;">
                            <p style="margin: 0; font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;">NexaDesk Support</p>
                            <h1 style="margin: 8px 0 0; font-size: 22px;">Ticket Received</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 28px; font-size: 15px; line-height: 1.7;">
                            <p>Halo {{ $ticket->email_from_name ?: ($ticket->user->name ?? 'User') }},</p>
                            <p>Terima kasih telah menghubungi NexaDesk Support.</p>
                            <p>Ticket Anda telah berhasil dibuat.</p>
                            <p><strong>Ticket ID:</strong><br>#{{ $ticket->id }}</p>
                            <p><strong>Subject:</strong><br>{{ $ticket->title }}</p>
                            <p><strong>Status:</strong><br>Open</p>
                            <p>Tim kami akan segera melakukan peninjauan terhadap laporan Anda.</p>
                            <p style="margin-bottom: 0;">Salam,<br>NexaDesk Support Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
