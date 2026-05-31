<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <p class="text-sm text-slate-500">NexaDesk Staff</p>
                <h1 class="text-3xl font-bold text-slate-900">Staff Tickets</h1>
                <p class="text-slate-500 mt-1">
                    Search, filter, and manage all submitted support tickets.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100">
                    <form method="GET" action="{{ route('staff.tickets.index') }}">
                        <div class="flex gap-3 flex-wrap">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search ticket..."
                                class="rounded-xl border-slate-300 text-sm">

                            <select name="status" class="rounded-xl border-slate-300 text-sm">
                                <option value="">All Status</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>

                            <select name="priority" class="rounded-xl border-slate-300 text-sm">
                                <option value="">All Priority</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-medium">
                                <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                                Search
                            </button>

                            <a href="{{ route('staff.tickets.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm text-slate-600 hover:bg-slate-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">

                    <thead class="bg-slate-100 text-slate-600 border-b border-slate-200">
                                <tr>
                                <th class="px-6 py-3 text-left font-medium">Ticket</th>
                                <th class="px-6 py-3 text-left font-medium">Reporter</th>
                                <th class="px-6 py-3 text-left font-medium">Priority</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Created</th>
                                <th class="px-6 py-3 text-right font-medium">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse ($tickets as $ticket)
                                    <tr class="hover:bg-blue-50/50 transition cursor-pointer">
                                        <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $ticket->title }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            #{{ $ticket->id }} · {{ \Illuminate\Support\Str::limit($ticket->description, 55) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold">
                                                {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                            </div>

                                            <span class="text-slate-700">
                                                {{ $ticket->user->name ?? 'Unknown' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($ticket->priority === 'high')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">High</span>
                                        @elseif ($ticket->priority === 'medium')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Medium</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Low</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($ticket->status === 'open')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Open</span>
                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">In Progress</span>
                                        @elseif ($ticket->status === 'resolved')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Resolved</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Closed</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                       <a href="{{ route('tickets.show', $ticket) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-blue-600 hover:bg-blue-100 hover:text-blue-700 font-medium transition">
                                            View
                                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                        No tickets found.
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