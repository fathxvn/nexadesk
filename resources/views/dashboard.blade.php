<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        @php
            $activeTickets = $openTickets + $inProgressTickets;
            $finishedTickets = $resolvedTickets + $closedTickets;
            $totalForPercent = max($totalTickets, 1);

            $statusItems = [
                [
                    'label' => 'Open',
                    'value' => $openTickets,
                    'helper' => 'Waiting for triage',
                    'icon' => 'heroicon-o-inbox',
                    'iconClass' => 'bg-blue-50 text-blue-600',
                    'barClass' => 'bg-blue-500',
                ],
                [
                    'label' => 'In Progress',
                    'value' => $inProgressTickets,
                    'helper' => 'Currently being handled',
                    'icon' => 'heroicon-o-clock',
                    'iconClass' => 'bg-amber-50 text-amber-600',
                    'barClass' => 'bg-amber-500',
                ],
                [
                    'label' => 'Resolved',
                    'value' => $resolvedTickets,
                    'helper' => 'Solution delivered',
                    'icon' => 'heroicon-o-check-circle',
                    'iconClass' => 'bg-emerald-50 text-emerald-600',
                    'barClass' => 'bg-emerald-500',
                ],
                [
                    'label' => 'Closed',
                    'value' => $closedTickets,
                    'helper' => 'Completed requests',
                    'icon' => 'heroicon-o-lock-closed',
                    'iconClass' => 'bg-slate-100 text-slate-600',
                    'barClass' => 'bg-slate-500',
                ],
            ];

            $metricCards = [
                [
                    'label' => 'Total Tickets',
                    'value' => $totalTickets,
                    'helper' => 'All requests in your workspace',
                    'icon' => 'heroicon-o-ticket',
                    'iconClass' => 'bg-indigo-50 text-indigo-600',
                ],
                [
                    'label' => 'Open',
                    'value' => $openTickets,
                    'helper' => 'Needs first response',
                    'icon' => 'heroicon-o-inbox',
                    'iconClass' => 'bg-blue-50 text-blue-600',
                ],
                [
                    'label' => 'In Progress',
                    'value' => $inProgressTickets,
                    'helper' => 'Assigned or under review',
                    'icon' => 'heroicon-o-clock',
                    'iconClass' => 'bg-amber-50 text-amber-600',
                ],
                [
                    'label' => 'Resolved',
                    'value' => $resolvedTickets,
                    'helper' => 'Completed with resolution',
                    'icon' => 'heroicon-o-check-circle',
                    'iconClass' => 'bg-emerald-50 text-emerald-600',
                ],
            ];
        @endphp

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">NexaDesk Overview</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">
                        Welcome back, {{ auth()->user()->name }}.
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">
                        Track ticket health, response workload, and recent support activity from one focused workspace.
                    </p>
                </div>

                <a
                    href="{{ route('tickets.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    <x-heroicon-o-plus-circle class="h-5 w-5" />
                    Create Ticket
                </a>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($metricCards as $card)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500">{{ $card['label'] }}</p>
                                <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-800">
                                    {{ $card['value'] }}
                                </p>
                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $card['iconClass'] }}">
                                <x-dynamic-component :component="$card['icon']" class="h-5 w-5" />
                            </div>
                        </div>

                        <p class="mt-4 text-sm text-slate-500">{{ $card['helper'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mb-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-800">Ticket Overview</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                A quick operational snapshot of active and completed support work.
                            </p>
                        </div>

                        <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700">
                            {{ $recentTickets->count() }} recent
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">Active Work</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-800">{{ $activeTickets }}</p>
                            <p class="mt-1 text-xs text-slate-500">Open and in-progress tickets</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">Completed</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-800">{{ $finishedTickets }}</p>
                            <p class="mt-1 text-xs text-slate-500">Resolved and closed tickets</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-sm font-medium text-slate-500">Resolution Share</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-800">
                                {{ round(($finishedTickets / $totalForPercent) * 100) }}%
                            </p>
                            <p class="mt-1 text-xs text-slate-500">Completed out of total tickets</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-800">Status Summary</h2>
                    <p class="mt-1 text-sm text-slate-500">Distribution across ticket states.</p>

                    <div class="mt-5 space-y-4">
                        @foreach ($statusItems as $item)
                            @php
                                $percent = round(($item['value'] / $totalForPercent) * 100);
                            @endphp

                            <div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $item['iconClass'] }}">
                                            <x-dynamic-component :component="$item['icon']" class="h-4 w-4" />
                                        </div>

                                        <div>
                                            <p class="text-sm font-medium text-slate-700">{{ $item['label'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $item['helper'] }}</p>
                                        </div>
                                    </div>

                                    <span class="text-sm font-semibold text-slate-700">{{ $item['value'] }}</span>
                                </div>

                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $item['barClass'] }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Recent Tickets</h2>
                        <p class="mt-1 text-sm text-slate-500">Latest support requests and their current state.</p>
                    </div>

                    <a
                        href="{{ route('tickets.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700"
                    >
                        View all tickets
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/80 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Ticket</th>
                                <th class="px-6 py-3 text-left font-semibold">Reporter</th>
                                <th class="px-6 py-3 text-left font-semibold">Priority</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Created</th>
                                <th class="px-6 py-3 text-right font-semibold">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentTickets as $ticket)
                                <tr class="transition hover:bg-indigo-50/30">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $ticket->title }}</div>
                                        <div class="mt-1 text-xs text-slate-500">
                                            #{{ $ticket->id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->description, 48) }}
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

                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route('tickets.show', $ticket) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100"
                                        >
                                            View
                                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                            <x-heroicon-o-ticket class="h-6 w-6" />
                                        </div>
                                        <h3 class="mt-4 text-sm font-semibold text-slate-700">No tickets yet</h3>
                                        <p class="mt-1 text-sm text-slate-500">Create your first support ticket to start tracking work.</p>
                                        <a
                                            href="{{ route('tickets.create') }}"
                                            class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                        >
                                            Create Ticket
                                        </a>
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
