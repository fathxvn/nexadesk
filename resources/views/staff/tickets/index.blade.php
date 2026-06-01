<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6">
                <p class="text-sm font-medium text-indigo-600">Staff Workspace</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">Staff Tickets</h1>
                <p class="mt-2 text-sm text-slate-500">Search, filter, and manage all submitted support requests.</p>
            </div>

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('staff.tickets.index') }}">
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Search</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search ticket..."
                                class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Priority</label>
                            <select name="priority" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Priority</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-3">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                                Search
                            </button>

                            <a href="{{ route('staff.tickets.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">All Tickets</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $tickets->count() }} tickets match the current filters.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/80 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Ticket</th>
                                <th class="px-6 py-3 text-left font-semibold">Reporter</th>
                                <th class="px-6 py-3 text-left font-semibold">Assigned To</th>
                                <th class="px-6 py-3 text-left font-semibold">Priority</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Created</th>
                                <th class="px-6 py-3 text-right font-semibold">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tickets as $ticket)
                                <tr class="transition hover:bg-indigo-50/30">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $ticket->title }}</div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            #{{ $ticket->id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->description, 60) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                                {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-slate-600">{{ $ticket->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($ticket->assignedTechnician)
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700">
                                                    {{ strtoupper(substr($ticket->assignedTechnician->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-slate-700">{{ $ticket->assignedTechnician->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ ucfirst($ticket->assignedTechnician->role) }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">Unassigned</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($ticket->priority === 'high')
                                            <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                                        @elseif ($ticket->priority === 'medium')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($ticket->status === 'open')
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">In Progress</span>
                                        @elseif ($ticket->status === 'resolved')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-500">{{ $ticket->created_at->diffForHumans() }}</td>

                                    <td class="px-6 py-4 text-right">
                                       <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100">
                                            View
                                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                            <x-heroicon-o-ticket class="h-6 w-6" />
                                        </div>
                                        <h3 class="mt-4 text-sm font-semibold text-slate-700">No tickets found</h3>
                                        <p class="mt-1 text-sm text-slate-500">Try adjusting search or filter criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
