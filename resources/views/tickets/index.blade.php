<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        @php
            $categoryLabels = [
                'network' => 'Network',
                'hardware' => 'Hardware',
                'software' => 'Software',
                'email' => 'Email',
                'account_access' => 'Account Access',
                'printer' => 'Printer',
                'other' => 'Other',
            ];

            $categoryClasses = [
                'network' => 'bg-blue-50 text-blue-700 ring-blue-100',
                'hardware' => 'bg-amber-50 text-amber-700 ring-amber-100',
                'software' => 'bg-violet-50 text-violet-700 ring-violet-100',
                'email' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                'account_access' => 'bg-cyan-50 text-cyan-700 ring-cyan-100',
                'printer' => 'bg-orange-50 text-orange-700 ring-orange-100',
                'other' => 'bg-slate-50 text-slate-600 ring-slate-200',
            ];
        @endphp

        <div class="mx-auto w-full max-w-none px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">Support Requests</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">My Tickets</h1>
                    <p class="mt-2 text-sm text-slate-500">Track, filter, and review your submitted support requests.</p>
                </div>

                <a
                    href="{{ route('tickets.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                    <x-heroicon-o-plus-circle class="h-5 w-5" />
                    Create Ticket
                </a>
            </div>

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('tickets.index') }}">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <div class="w-full sm:max-w-xs">
                            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                            <select id="status" name="status" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                                <x-heroicon-o-funnel class="h-4 w-4" />
                                Filter
                            </button>

                            <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Ticket Queue</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $tickets->count() }} tickets currently visible.</p>
                    </div>
                </div>

                <div>
                    <table class="w-full table-fixed text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/70 text-xs font-medium uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="w-[30%] px-5 py-3 text-left">Ticket</th>
                                <th class="w-[13%] px-4 py-3 text-left">Category</th>
                                <th class="w-[15%] px-4 py-3 text-left">Department</th>
                                <th class="w-[15%] px-4 py-3 text-left">State</th>
                                <th class="w-[13%] px-4 py-3 text-left">SLA</th>
                                <th class="w-[8%] px-4 py-3 text-left">Created</th>
                                <th class="w-[6%] px-5 py-3 text-right">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tickets as $ticket)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-5 py-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                                                <x-heroicon-o-ticket class="h-4 w-4" />
                                            </div>

                                            <div class="min-w-0">
                                                <div class="truncate font-semibold text-slate-800">{{ $ticket->title }}</div>
                                                <div class="mt-0.5 truncate text-xs text-slate-500">
                                                    #{{ $ticket->id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->description, 90) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">
                                        @php
                                            $category = $ticket->category ?? 'other';
                                        @endphp
                                        <span class="inline-flex max-w-full items-center truncate whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $categoryClasses[$category] ?? $categoryClasses['other'] }}">
                                            {{ $categoryLabels[$category] ?? $categoryLabels['other'] }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">
                                            @if ($ticket->department)
                                                <span class="inline-flex max-w-full items-center truncate whitespace-nowrap rounded-full bg-cyan-50 px-3 py-1 text-xs font-medium text-cyan-700 ring-1 ring-inset ring-cyan-100">
                                                    {{ $ticket->department->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex max-w-full items-center truncate whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">
                                                    No Department
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1.5">
                                                @if ($ticket->status === 'open')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                            @elseif ($ticket->status === 'in_progress')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">In Progress</span>
                                            @elseif ($ticket->status === 'resolved')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                            @else
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                            @endif

                                            @if ($ticket->priority === 'high')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                                            @elseif ($ticket->priority === 'medium')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                                            @else
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $ticket->slaBadgeClasses() }}">
                                            {{ $ticket->slaLabel() }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</td>

                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('tickets.show', $ticket) }}" title="View ticket" class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                                            <span class="sr-only">View ticket</span>
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
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
                                        <p class="mt-1 text-sm text-slate-500">Create a ticket or adjust your filters to see requests here.</p>
                                        <a href="{{ route('tickets.create') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
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
