<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketDepartmentRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_determines_department_without_assigning_a_technician(): void
    {
        $departments = collect([
            'IT Support',
            'Network',
            'Server',
            'Application',
        ])->mapWithKeys(function (string $name) {
            $department = Department::create(['name' => $name]);

            return [$name => $department];
        });

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'title' => 'VPN tidak tersambung',
            'description' => 'VPN kantor tidak dapat digunakan.',
            'category' => 'network',
            'priority' => 'high',
            'department_id' => $departments['Application']->id,
        ]);

        $response->assertRedirect(route('tickets.index'));

        $ticket = Ticket::sole();

        $this->assertSame($departments['Network']->id, $ticket->department_id);
        $this->assertNull($ticket->assigned_to_user_id);
        $this->assertSame('open', $ticket->status);
        $this->assertNotNull($ticket->sla_started_at);
        $this->assertNotNull($ticket->sla_due_at);
        $this->assertDatabaseHas('ticket_activities', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'type' => 'created',
            'description' => 'Ticket created',
        ]);
    }
}
