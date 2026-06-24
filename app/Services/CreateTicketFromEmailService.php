<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CreateTicketFromEmailService
{
    public function create(array $email): Ticket
    {
        $payload = $this->normalize($email);

        $existingTicket = Ticket::where('email_message_id', $payload['message_id'])->first();

        if ($existingTicket) {
            return $existingTicket;
        }

        return DB::transaction(function () use ($payload) {
            $existingTicket = Ticket::where('email_message_id', $payload['message_id'])
                ->lockForUpdate()
                ->first();

            if ($existingTicket) {
                return $existingTicket;
            }

            $requester = User::firstOrCreate(
                ['email' => $payload['from_email']],
                [
                    'name' => $payload['from_name'],
                    'password' => Hash::make(Str::password(32)),
                    'role' => 'user',
                ]
            );

            $category = $this->detectCategory(
                $payload['subject'].' '.$payload['body']
            );
            $department = Department::where(
                'name',
                $this->departmentForCategory($category)
            )->firstOrFail();
            $slaStartedAt = $payload['received_at'];
            $slaDueAt = (new Ticket)->slaDueAtForPriority('medium', $slaStartedAt);

            $ticket = Ticket::create([
                'user_id' => $requester->id,
                'assigned_to_user_id' => null,
                'department_id' => $department->id,
                'title' => Str::limit($payload['subject'], 255, ''),
                'description' => $payload['body'],
                'category' => $category,
                'priority' => 'medium',
                'status' => 'open',
                'source' => 'email',
                'email_from' => $payload['from_email'],
                'email_from_name' => $payload['from_name'],
                'email_subject' => $payload['subject'],
                'email_body' => $payload['body'],
                'email_message_id' => $payload['message_id'],
                'email_received_at' => $payload['received_at'],
                'sla_started_at' => $slaStartedAt,
                'sla_due_at' => $slaDueAt,
            ]);

            $ticket->activities()->create([
                'user_id' => null,
                'type' => 'email_imported',
                'description' => 'Ticket created from incoming email',
            ]);

            return $ticket;
        });
    }

    private function normalize(array $email): array
    {
        $messageId = trim((string) ($email['message_id'] ?? ''));
        $fromEmail = strtolower(trim((string) ($email['from_email'] ?? '')));
        $subject = trim((string) ($email['subject'] ?? ''));
        $body = trim((string) ($email['body'] ?? ''));

        if ($messageId === '') {
            throw new InvalidArgumentException('Incoming email message_id is required.');
        }

        if (filter_var($fromEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Incoming email sender address is invalid.');
        }

        if ($subject === '') {
            $subject = '(No subject)';
        }

        if ($body === '') {
            $body = '(No message body)';
        }

        return [
            'message_id' => $messageId,
            'from_email' => $fromEmail,
            'from_name' => trim((string) ($email['from_name'] ?? '')) ?: $fromEmail,
            'subject' => $subject,
            'body' => $body,
            'received_at' => CarbonImmutable::parse($email['received_at'] ?? now()),
        ];
    }

    private function detectCategory(string $content): string
    {
        $content = Str::lower($content);

        $keywordMap = [
            'network' => ['network', 'internet', 'wifi', 'wi-fi', 'vpn', 'router'],
            'printer' => ['printer', 'printing', 'print', 'scanner'],
            'hardware' => ['hardware', 'laptop', 'monitor', 'keyboard', 'mouse', 'computer'],
            'account_access' => ['account', 'access', 'login', 'password', 'locked'],
            'software' => ['software', 'application', 'app', 'system error'],
            'email' => ['outlook', 'mailbox', 'email delivery', 'cannot send email', 'cannot receive email'],
        ];

        foreach ($keywordMap as $category => $keywords) {
            if (Str::contains($content, $keywords)) {
                return $category;
            }
        }

        return 'other';
    }

    private function departmentForCategory(string $category): string
    {
        return match ($category) {
            'network' => 'Network',
            'software', 'email', 'account_access' => 'Application',
            default => 'IT Support',
        };
    }
}
