<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ticket->title }}</title>
</head>
<body style="margin: 0; background-color: #f8fafc; color: #334155; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 640px; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;">
                    <tr>
                        <td style="background-color: #4f46e5; padding: 24px 28px;">
                            <p style="margin: 0; color: #c7d2fe; font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;">
                                NexaDesk Helpdesk
                            </p>
                            <h1 style="margin: 8px 0 0; color: #ffffff; font-size: 22px; line-height: 1.35;">
                                Reply for Ticket #{{ $ticket->id }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 28px;">
                            <p style="margin: 0 0 18px; font-size: 15px; line-height: 1.6;">
                                Hello {{ $ticket->email_from_name ?: 'there' }},
                            </p>

                            <p style="margin: 0 0 12px; color: #64748b; font-size: 13px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;">
                                Support reply
                            </p>

                            <div style="border-left: 4px solid #4f46e5; border-radius: 8px; background-color: #f8fafc; padding: 18px; color: #334155; font-size: 15px; line-height: 1.7; white-space: pre-line;">{{ $replyMessage }}</div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                <tr>
                                    <td style="padding: 4px 0; color: #64748b; font-size: 13px;">Ticket</td>
                                    <td align="right" style="padding: 4px 0; color: #334155; font-size: 13px; font-weight: 700;">
                                        #{{ $ticket->id }} — {{ $ticket->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 0; color: #64748b; font-size: 13px;">Status</td>
                                    <td align="right" style="padding: 4px 0; color: #334155; font-size: 13px; font-weight: 700;">
                                        {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; color: #64748b; font-size: 13px; line-height: 1.6;">
                                Regards,<br>
                                NexaDesk Support
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
