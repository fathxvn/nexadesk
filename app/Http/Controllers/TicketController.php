<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketInternalNote;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $query = Ticket::where('user_id', auth()->id());

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $tickets = $query
            ->with(['assignedTechnician'])
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('tickets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required|in:network,hardware,software,email,account_access,printer,other',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'priority' => 'required|in:low,medium,high',
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $ticketData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'department_id' => $validated['department_id'] ?? null,
            'status' => 'open',
            'sla_started_at' => now(),
        ];

        $ticketData['sla_due_at'] = (new Ticket())->slaDueAtForPriority(
            $validated['priority'],
            $ticketData['sla_started_at']
        );

        if ($request->hasFile('attachment')) {
            $ticketData['attachment_path'] = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket = auth()->user()->tickets()->create($ticketData);

        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'type' => 'created',
            'description' => 'Ticket created',
        ]);

        return redirect()->route('tickets.index')
            ->with('notification', [
                'type' => 'success',
                'title' => 'Ticket created',
                'message' => 'The ticket has been submitted successfully.',
            ]);
    }

    public function show(Ticket $ticket)
    {
        if (! auth()->user()->isStaff() && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load([
            'user', 
            'comments.user', 
            'assignedTechnician',
            'department',
            'activities.user',
            ]);

        if (auth()->user()->isStaff()) {
            $ticket->load('internalNotes.user');
        }

        $technicians = User::whereIn('role', ['admin', 'technician'])
            ->orderBy('name')
            ->get();

        return view('tickets.show', compact('ticket', 'technicians'));
    }

    public function edit(Ticket $ticket)
    {
        $departments = Department::orderBy('name')->get();

        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tickets.edit', compact('ticket', 'departments'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required|in:network,hardware,software,email,account_access,printer,other',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($request->hasFile('attachment')) {
            if ($ticket->attachment_path) {
                Storage::disk('public')->delete($ticket->attachment_path);
            }

            $validated['attachment_path'] = $request->file('attachment')->store('tickets', 'public');
        }

        unset($validated['attachment']);

        if ($ticket->priority !== $validated['priority'] && ! $ticket->isSlaCompleted()) {
            $validated['sla_due_at'] = $ticket->slaDueAtForPriority(
                $validated['priority'],
                $ticket->sla_started_at ?? now()
            );
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('notification', [
                'type' => 'info',
                'title' => 'Ticket updated',
                'message' => 'The ticket details have been updated.',
            ]);
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        if ($ticket->attachment_path) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('notification', [
                'type' => 'danger',
                'title' => 'Ticket deleted',
                'message' => 'The ticket has been permanently deleted.',
            ]);
    }

    public function staffIndex()
    {
        $query = Ticket::query();

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('priority')) {
            $query->where('priority', request('priority'));
        }

        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        $tickets = $query
            ->with(['user', 'assignedTechnician', 'department'])
            ->latest()
            ->get();

        return view('staff.tickets.index', compact('tickets'));
    }

    public function assignedTickets()
    {
        $query = Ticket::query()
            ->whereNotNull('assigned_to_user_id');

        if (auth()->user()->role === 'technician') {
            $query->where('assigned_to_user_id', auth()->id());
        }

        $tickets = $query
            ->with(['user', 'assignedTechnician', 'department'])
            ->latest()
            ->get();

        return view('staff.tickets.assigned', compact('tickets'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);
        $oldStatus = $ticket->status;

        $statusData = [
            'status' => $validated['status'],
        ];

        if (in_array($validated['status'], ['resolved', 'closed'], true)) {
            $statusData['sla_resolved_at'] = now();
        } else {
            $statusData['sla_resolved_at'] = null;
        }

        if ($ticket->sla_due_at && now()->greaterThan($ticket->sla_due_at)) {
            $statusData['sla_breached_at'] = $ticket->sla_breached_at ?? now();
        }

        $ticket->update($statusData);


        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'type' => 'status_changed',
            'description' => 'Status changed from ' . ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' . ucfirst(str_replace('_', ' ', $validated['status'])),
        ]);

        return back()->with('notification', [
            'type' => 'info',
            'title' => 'Status updated',
            'message' => 'Ticket status has been updated.',
        ]);
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        if (! auth()->user()->isStaff() && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->comments()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'type' => 'comment',
            'description' => 'Comment added',
        ]);

        return back()->with('notification', [
            'type' => 'success',
            'title' => 'Comment added',
            'message' => 'Your comment has been posted.',
        ]);
    }

    public function storeInternalNote(Request $request, Ticket $ticket)
    {
        if (! auth()->user()->isStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        TicketInternalNote::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return back()->with('notification', [
            'type' => 'warning',
            'title' => 'Internal note added',
            'message' => 'Private note has been saved.',
        ]);
    }

    public function assignTechnician(Request $request, Ticket $ticket)
        {
            $validated = $request->validate([
                'assigned_to_user_id' => 'nullable|exists:users,id',
            ]);

            $technician = User::find($validated['assigned_to_user_id']);

            $ticket->update([
                'assigned_to_user_id' => $validated['assigned_to_user_id'],
            ]);

            $ticket->activities()->create([
                'user_id' => auth()->id(),
                'type' => 'assigned',
                'description' => $technician
                    ? 'Assigned to ' . $technician->name
                    : 'Technician unassigned',
            ]);

            return back()->with('notification', [
                'type' => 'info',
                'title' => 'Technician assigned',
                'message' => 'The ticket has been assigned successfully.',
            ]);
        }
}
