<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Ticket::query();

        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $totalTickets = (clone $query)->count();
        $openTickets = (clone $query)->where('status', 'open')->count();
        $inProgressTickets = (clone $query)->where('status', 'in_progress')->count();
        $resolvedTickets = (clone $query)->where('status', 'resolved')->count();
        $closedTickets = (clone $query)->where('status', 'closed')->count();
        $slaTickets = (clone $query)->get();
        $slaOverdueTickets = $slaTickets
            ->filter(fn (Ticket $ticket) => $ticket->slaStatus() === 'overdue')
            ->count();
        $slaDueSoonTickets = $slaTickets
            ->filter(fn (Ticket $ticket) => $ticket->slaStatus() === 'due_soon')
            ->count();
        $slaOnTrackTickets = $slaTickets
            ->filter(fn (Ticket $ticket) => $ticket->slaStatus() === 'on_track')
            ->count();
        $slaCompletedTickets = $slaTickets
            ->filter(fn (Ticket $ticket) => $ticket->slaStatus() === 'completed')
            ->count();

        $recentTickets = (clone $query)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'slaOverdueTickets',
            'slaDueSoonTickets',
            'slaOnTrackTickets',
            'slaCompletedTickets',
            'recentTickets'
        ));
    }
}
