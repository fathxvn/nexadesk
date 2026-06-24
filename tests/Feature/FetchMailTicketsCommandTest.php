<?php

namespace Tests\Feature;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchMailTicketsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_imports_a_sample_email_and_skips_duplicate_message_ids(): void
    {
        foreach (['IT Support', 'Network', 'Server', 'Application'] as $name) {
            Department::create(['name' => $name]);
        }

        $sample = json_encode([
            'message_id' => '<command-message@example.com>',
            'from_email' => 'command.sender@example.com',
            'from_name' => 'Command Sender',
            'subject' => 'Printer is offline',
            'body' => 'The finance printer cannot print.',
            'received_at' => '2026-06-25 10:00:00',
        ], JSON_THROW_ON_ERROR);

        $this->artisan('mail:fetch-tickets', ['--sample' => $sample])
            ->assertSuccessful();

        $this->artisan('mail:fetch-tickets', ['--sample' => $sample])
            ->assertSuccessful();

        $this->assertDatabaseCount('tickets', 1);
        $this->assertDatabaseHas('tickets', [
            'email_message_id' => '<command-message@example.com>',
            'category' => 'printer',
            'source' => 'email',
        ]);
    }
}
