<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = auth()->user()->tickets()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

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
            'activities.user',
            ]);

        $technicians = User::whereIn('role', ['admin', 'technician'])
            ->orderBy('name')
            ->get();

        return view('tickets.show', compact('ticket', 'technicians'));
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high',
        ]);

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
            ->with(['user', 'assignedTechnician'])
            ->latest()
            ->get();

        return view('staff.tickets.index', compact('tickets'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);
        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => $validated['status'],
        ]);


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
                'type' => 'success',
                'title' => 'Technician assigned',
                'message' => 'The ticket has been assigned successfully.',
            ]);
        }
}
