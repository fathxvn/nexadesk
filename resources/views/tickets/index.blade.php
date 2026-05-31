<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-sm text-slate-500">NexaDesk</p>
                    <h1 class="text-3xl font-bold text-slate-900">My Tickets</h1>
                    <p class="text-slate-500 mt-1">
                        Track and manage your submitted support requests.
                    </p>
                </div>

                <a href="{{ route('tickets.create') }}"
                   class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-slate-800 transition">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Create Ticket
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100">
                    <form method="GET" action="{{ route('tickets.index') }}">
                        <div class="flex gap-3 flex-wrap">
                            <select name="status" class="rounded-xl border-slate-300 text-sm">
                                <option value="">All Status</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-medium">
                                <x-heroicon-o-funnel class="w-4 h-4" />
                                Filter
                            </button>

                            <a href="{{ route('tickets.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm text-slate-600 hover:bg-slate-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Ticket</th>
                                <th class="px-6 py-3 text-left font-medium">Priority</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Created</th>
                                <th class="px-6 py-3 text-right font-medium">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tickets as $ticket)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                                                <x-heroicon-o-ticket class="w-5 h-5" />
                                            </div>

                                            <div>
                                                <div class="font-semibold text-slate-900">
                                                    {{ $ticket->title }}
                                                </div>
                                                <div class="text-xs text-slate-500 mt-1">
                                                    #{{ $ticket->id }} · {{ \Illuminate\Support\Str::limit($ticket->description, 60) }}
                                                </div>
                                            </div>
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
                                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                            View
                                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                        Belum ada ticket.
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