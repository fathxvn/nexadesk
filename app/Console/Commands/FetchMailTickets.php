<?php

namespace App\Console\Commands;

use App\Services\CreateTicketFromEmailService;
use Illuminate\Console\Command;
use JsonException;
use Throwable;
use Webklex\IMAP\Facades\Client;

class FetchMailTickets extends Command
{
    protected $signature = 'mail:fetch-tickets
                            {--sample= : JSON file path or raw JSON payload for Mail-to-Ticket V1}';

    protected $description = 'Import incoming support emails as NexaDesk tickets';

    public function handle(CreateTicketFromEmailService $service): int
    {
        $sample = $this->option('sample');

        return blank($sample)
            ? $this->fetchFromImap($service)
            : $this->fetchFromSample($service, (string) $sample);
    }

    private function fetchFromSample(CreateTicketFromEmailService $service, string $sample): int
    {
        try {
            $emails = $this->readSample($sample);
        } catch (Throwable $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->components->info('Sample payload loaded.');

        $created = 0;
        $duplicates = 0;
        $failed = 0;

        foreach ($emails as $index => $email) {
            try {
                $ticket = $service->create($email);

                if ($ticket->wasRecentlyCreated) {
                    $created++;
                    $this->components->info("Created ticket #{$ticket->id} from {$ticket->email_from}.");
                } else {
                    $duplicates++;
                    $this->line("Skipped duplicate message {$ticket->email_message_id} (ticket #{$ticket->id}).");
                }
            } catch (Throwable $exception) {
                $failed++;
                report($exception);
                $this->components->error('Email '.($index + 1).' failed: '.$exception->getMessage());
            }
        }

        $this->newLine();
        $this->table(
            ['Created', 'Duplicates', 'Failed'],
            [[$created, $duplicates, $failed]]
        );
        $this->line('Sample mode does not delete or modify any inbox message.');

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function fetchFromImap(CreateTicketFromEmailService $service): int
    {
        $client = Client::account(config('imap.default'));

        try {
            $client->connect();
            $this->components->info('Connected to IMAP inbox.');

            $inbox = $client->getFolder('INBOX');

            if (! $inbox) {
                $this->components->error('INBOX folder was not found.');

                return self::FAILURE;
            }

            $messages = $inbox->messages()
                ->unseen()
                ->leaveUnread()
                ->get();

            $this->line('Found '.$messages->count().' unread messages.');

            $created = 0;
            $duplicates = 0;
            $failed = 0;

            foreach ($messages as $message) {
                try {
                    $from = $message->getFrom()->first();
                    $messageId = trim($message->getMessageId()->toString());

                    if ($messageId === '') {
                        $messageId = 'imap-uid:'.$message->getUid();
                    }

                    $ticket = $service->create([
                        'message_id' => $messageId,
                        'from_email' => $from?->mail,
                        'from_name' => $from?->personal,
                        'subject' => $message->getSubject()->toString(),
                        'body' => $this->messageBody($message),
                        'received_at' => $message->getDate()->toDate(),
                    ]);

                    if ($ticket->wasRecentlyCreated) {
                        $created++;
                        $this->components->info("Created ticket #{$ticket->id} from {$ticket->email_from}.");
                    } else {
                        $duplicates++;
                        $this->line("Skipped duplicate message {$ticket->email_message_id} (ticket #{$ticket->id}).");
                    }

                    $message->setFlag('Seen');
                } catch (Throwable $exception) {
                    $failed++;
                    report($exception);
                    $this->components->error('Failed message: '.$exception->getMessage());
                }
            }

            $this->newLine();
            $this->table(
                ['Created', 'Duplicates', 'Failed'],
                [[$created, $duplicates, $failed]]
            );
            $this->line('Messages were not deleted. Successfully processed messages were marked as read.');

            return $failed === 0 ? self::SUCCESS : self::FAILURE;
        } catch (Throwable $exception) {
            report($exception);
            $this->components->error('IMAP connection or fetch failed: '.$exception->getMessage());

            return self::FAILURE;
        } finally {
            try {
                $client->disconnect();
            } catch (Throwable) {
                // The connection may not have been established.
            }
        }
    }

    private function messageBody($message): string
    {
        $textBody = trim($message->getTextBody());

        if ($textBody !== '') {
            return $textBody;
        }

        return trim(strip_tags($message->getHTMLBody()));
    }

    private function readSample(string $sample): array
    {
        $json = is_file($sample)
            ? file_get_contents($sample)
            : $sample;

        if ($json === false) {
            throw new JsonException('Unable to read the sample JSON file.');
        }

        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new JsonException('Sample JSON must decode to an object or array.');
        }

        return array_is_list($decoded) ? $decoded : [$decoded];
    }
}
