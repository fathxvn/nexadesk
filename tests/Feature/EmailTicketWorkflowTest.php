<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CreateTicketFromEmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_the_email_ticket_queue(): void
    {
        $staff = User::factory()->create(['role' => 'admin']);
        $emailTicket = $this->ticket(['source' => 'email', 'title' => 'Email queue ticket']);
        $webTicket = $this->ticket(['source' => 'web', 'title' => 'Web-only ticket']);

        $response = $this->actingAs($staff)
            ->get(route('staff.email-tickets.index'));

        $response
            ->assertOk()
            ->assertViewIs('staff.tickets.email-index')
            ->assertSee('Email queue ticket')
            ->assertDontSee('Web-only ticket')
            ->assertSee(route('tickets.show', $emailTicket));

        $this->assertNotSame($emailTicket->id, $webTicket->id);
    }

    public function test_regular_users_cannot_view_the_email_ticket_queue(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('staff.email-tickets.index'))
            ->assertForbidden();
    }

    public function test_compose_reply_card_is_visible_to_staff_for_email_tickets(): void
    {
        $staff = User::factory()->create(['role' => 'technician']);
        $ticket = $this->ticket([
            'source' => ' EMAIL ',
            'email_from' => 'sender@example.com',
        ]);

        $this->actingAs($staff)
            ->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Compose Email Reply')
            ->assertSee('sender@example.com')
            ->assertDontSee('email reply is not available');
    }

    public function test_web_ticket_shows_staff_information_instead_of_compose_card(): void
    {
        $staff = User::factory()->create(['role' => 'admin']);
        $ticket = $this->ticket(['source' => 'web']);

        $this->actingAs($staff)
            ->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertDontSee('Compose Email Reply')
            ->assertSee('This ticket was created from web form, email reply is not available.');
    }

    public function test_create_ticket_from_email_service_creates_ticket_and_prevents_duplicates(): void
    {
        $this->departments();

        $payload = [
            'message_id' => '<message-123@example.com>',
            'from_email' => 'new.sender@example.com',
            'from_name' => 'New Sender',
            'subject' => 'VPN cannot connect',
            'body' => 'Our office internet and VPN are unavailable.',
            'received_at' => '2026-06-25 09:30:00',
        ];

        $service = app(CreateTicketFromEmailService::class);
        $firstTicket = $service->create($payload);
        $duplicateTicket = $service->create($payload);

        $this->assertTrue($firstTicket->wasRecentlyCreated);
        $this->assertFalse($duplicateTicket->wasRecentlyCreated);
        $this->assertSame($firstTicket->id, $duplicateTicket->id);
        $this->assertSame('email', $firstTicket->source);
        $this->assertSame('network', $firstTicket->category);
        $this->assertSame('Network', $firstTicket->department->name);
        $this->assertNull($firstTicket->assigned_to_user_id);
        $this->assertNotNull($firstTicket->sla_started_at);
        $this->assertNotNull($firstTicket->sla_due_at);

        $this->assertDatabaseCount('tickets', 1);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('ticket_activities', [
            'ticket_id' => $firstTicket->id,
            'user_id' => null,
            'type' => 'email_imported',
            'description' => 'Ticket created from incoming email',
        ]);
    }

    private function ticket(array $attributes = []): Ticket
    {
        $requester = User::factory()->create(['role' => 'user']);

        return Ticket::create(array_merge([
            'user_id' => $requester->id,
            'title' => 'Support request',
            'description' => 'Please help with this support request.',
            'category' => 'other',
            'priority' => 'medium',
            'status' => 'open',
            'source' => 'web',
        ], $attributes));
    }

    private function departments(): void
    {
        foreach (['IT Support', 'Network', 'Server', 'Application'] as $name) {
            Department::create(['name' => $name]);
        }
    }
}
