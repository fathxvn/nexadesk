<?php

namespace App\Console\Commands;

use App\Services\CreateTicketFromEmailService;
use Illuminate\Console\Command;
use JsonException;
use Throwable;

class FetchMailTickets extends Command
{
    protected $signature = 'mail:fetch-tickets
                            {--sample= : JSON file path or raw JSON payload for Mail-to-Ticket V1}';

    protected $description = 'Import incoming support emails as NexaDesk tickets';

    public function handle(CreateTicketFromEmailService $service): int
    {
        $sample = $this->option('sample');

        if (blank($sample)) {
            $this->components->error('No IMAP adapter is installed or configured.');
            $this->line('Install an IMAP client such as webklex/php-imap, then connect it to this command.');
            $this->line('For V1 testing, pass --sample=/path/to/email.json or raw JSON.');

            return self::FAILURE;
        }

        try {
            $emails = $this->readSample((string) $sample);
        } catch (Throwable $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

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
