<?php

namespace Tests\Feature;

use App\Mail\TicketEmailReply;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class TicketEmailReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_send_an_email_reply_and_store_ticket_history(): void
    {
        Mail::fake();

        $staff = User::factory()->create(['role' => 'admin']);
        $ticket = $this->emailTicket();

        $response = $this->actingAs($staff)->post(
            route('staff.tickets.email-reply.store', $ticket),
            ['message' => 'We have reset your account. Please try signing in again.']
        );

        $response
            ->assertRedirect()
            ->assertSessionHas('notification.type', 'success');

        Mail::assertSent(TicketEmailReply::class, function (TicketEmailReply $mail) use ($ticket) {
            return $mail->hasTo('sender@example.com')
                && $mail->ticket->is($ticket)
                && $mail->replyMessage === 'We have reset your account. Please try signing in again.'
                && $mail->replySubject() === "Re: [NexaDesk #{$ticket->id}] Cannot access account";
        });

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $staff->id,
            'message' => 'We have reset your account. Please try signing in again.',
        ]);

        $this->assertDatabaseHas('ticket_activities', [
            'ticket_id' => $ticket->id,
            'user_id' => $staff->id,
            'type' => 'email_reply',
            'description' => 'Email reply sent to sender@example.com',
        ]);
    }

    public function test_email_reply_requires_a_message_in_the_named_error_bag(): void
    {
        Mail::fake();

        $staff = User::factory()->create(['role' => 'technician']);
        $ticket = $this->emailTicket();

        $response = $this->actingAs($staff)->post(
            route('staff.tickets.email-reply.store', $ticket),
            ['message' => '']
        );

        $response->assertSessionHasErrorsIn('emailReply', ['message']);
        Mail::assertNothingSent();
    }

    public function test_email_reply_is_rejected_when_the_recipient_is_missing(): void
    {
        Mail::fake();

        $staff = User::factory()->create(['role' => 'admin']);
        $ticket = $this->emailTicket(['email_from' => null]);

        $response = $this->actingAs($staff)->post(
            route('staff.tickets.email-reply.store', $ticket),
            ['message' => 'This reply cannot be delivered.']
        );

        $response->assertSessionHas('notification.type', 'danger');

        Mail::assertNothingSent();
        $this->assertDatabaseCount('ticket_comments', 0);
        $this->assertDatabaseCount('ticket_activities', 0);
    }

    public function test_failed_email_delivery_does_not_store_comment_or_activity(): void
    {
        $staff = User::factory()->create(['role' => 'admin']);
        $ticket = $this->emailTicket();

        Mail::shouldReceive('to')
            ->once()
            ->with('sender@example.com')
            ->andReturnSelf();

        Mail::shouldReceive('send')
            ->once()
            ->andThrow(new RuntimeException('SMTP unavailable'));

        $response = $this->actingAs($staff)->post(
            route('staff.tickets.email-reply.store', $ticket),
            ['message' => 'Please try the suggested troubleshooting steps.']
        );

        $response->assertSessionHas('notification.type', 'danger');

        $this->assertDatabaseCount('ticket_comments', 0);
        $this->assertDatabaseCount('ticket_activities', 0);
    }

    public function test_regular_users_cannot_send_email_replies(): void
    {
        Mail::fake();

        $user = User::factory()->create(['role' => 'user']);
        $ticket = $this->emailTicket();

        $this->actingAs($user)
            ->post(
                route('staff.tickets.email-reply.store', $ticket),
                ['message' => 'Unauthorized reply']
            )
            ->assertForbidden();

        Mail::assertNothingSent();
    }

    private function emailTicket(array $attributes = []): Ticket
    {
        $requester = User::factory()->create(['role' => 'user']);

        return Ticket::create(array_merge([
            'user_id' => $requester->id,
            'title' => 'Account access issue',
            'description' => 'The requester cannot access their account.',
            'category' => 'account_access',
            'priority' => 'medium',
            'status' => 'open',
            'source' => 'email',
            'email_from' => 'sender@example.com',
            'email_from_name' => 'Example Sender',
            'email_subject' => 'Cannot access account',
            'email_body' => 'Please help me access my account.',
            'email_received_at' => now(),
        ], $attributes));
    }
}
