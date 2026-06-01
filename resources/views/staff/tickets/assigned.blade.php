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
            <div class="mb-6">
                <p class="text-sm font-medium text-indigo-600">Staff Workspace</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">Assigned Tickets</h1>
                <p class="mt-2 text-sm text-slate-500">Review tickets that are currently assigned to a technician.</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Assigned Queue</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $tickets->count() }} assigned tickets visible.</p>
                    </div>
                </div>

                <div>
                    <table class="w-full table-fixed text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/70 text-xs font-medium uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="w-[28%] px-5 py-3 text-left">Ticket</th>
                                <th class="w-[12%] px-4 py-3 text-left">Category</th>
                                <th class="w-[14%] px-4 py-3 text-left">State</th>
                                <th class="w-[12%] px-4 py-3 text-left">SLA</th>
                                <th class="w-[20%] px-4 py-3 text-left">People</th>
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
                                        <div class="min-w-0">
                                            <div class="truncate font-semibold text-slate-800">{{ $ticket->title }}</div>
                                            <div class="mt-0.5 max-w-full truncate text-xs text-slate-500">
                                                #{{ $ticket->id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->description, 90) }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">
                                        @php
                                            $category = $ticket->category ?? 'other';
                                        @endphp
                                        <span class="inline-flex max-w-full truncate rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $categoryClasses[$category] ?? $categoryClasses['other'] }}">
                                            {{ $categoryLabels[$category] ?? $categoryLabels['other'] }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $ticket->slaBadgeClasses() }}">
                                            {{ $ticket->slaLabel() }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            @if ($ticket->status === 'open')
                                                <span class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                            @elseif ($ticket->status === 'in_progress')
                                                <span class="inline-flex rounded-md bg-violet-50 px-2 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">In Progress</span>
                                            @elseif ($ticket->status === 'resolved')
                                                <span class="inline-flex rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                            @else
                                                <span class="inline-flex rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                            @endif

                                            @if ($ticket->priority === 'high')
                                                <span class="inline-flex rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                                            @elseif ($ticket->priority === 'medium')
                                                <span class="inline-flex rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                                            @else
                                                <span class="inline-flex rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="space-y-1">
                                            <div class="flex min-w-0 items-center gap-2">
                                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[11px] font-semibold text-slate-600">
                                                    {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="min-w-0 text-xs">
                                                    <span class="text-slate-400">From</span>
                                                    <span class="truncate font-medium text-slate-700">{{ $ticket->user->name ?? 'Unknown' }}</span>
                                                </div>
                                            </div>

                                            <div class="flex min-w-0 items-center gap-2">
                                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-[11px] font-semibold text-indigo-700">
                                                    {{ strtoupper(substr($ticket->assignedTechnician->name ?? 'T', 0, 1)) }}
                                                </div>
                                                <div class="min-w-0 text-xs">
                                                    <span class="text-slate-400">To</span>
                                                    <span class="truncate font-medium text-slate-700">{{ $ticket->assignedTechnician->name ?? 'Unknown' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </td>

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
                                        <h3 class="mt-4 text-sm font-semibold text-slate-700">No assigned tickets</h3>
                                        <p class="mt-1 text-sm text-slate-500">Assigned tickets will appear here once a technician is selected.</p>
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
