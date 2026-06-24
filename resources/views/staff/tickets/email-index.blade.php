<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto w-full max-w-none px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">Staff Workspace</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">Email Tickets</h1>
                    <p class="mt-2 text-sm text-slate-500">Support requests created from incoming email messages.</p>
                </div>

                <span class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-100">
                    {{ $tickets->count() }} email tickets
                </span>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/70 text-xs font-medium uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3 text-left">Ticket ID</th>
                                <th class="px-4 py-3 text-left">Subject / Title</th>
                                <th class="px-4 py-3 text-left">From Email</th>
                                <th class="px-4 py-3 text-left">Department</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Assigned Technician</th>
                                <th class="px-4 py-3 text-left">Received At</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tickets as $ticket)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-indigo-700">#{{ $ticket->id }}</td>
                                    <td class="max-w-sm px-4 py-4">
                                        <p class="truncate font-semibold text-slate-800">{{ $ticket->email_subject ?: $ticket->title }}</p>
                                        <p class="mt-1 truncate text-xs text-slate-500">{{ $ticket->title }}</p>
                                    </td>
                                    <td class="max-w-xs px-4 py-4">
                                        <p class="truncate font-medium text-slate-700">{{ $ticket->email_from ?: '-' }}</p>
                                        <p class="mt-1 truncate text-xs text-slate-500">{{ $ticket->email_from_name ?: 'Unknown sender' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-medium text-cyan-700 ring-1 ring-inset ring-cyan-100">
                                            {{ $ticket->department->name ?? 'No Department' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        @if ($ticket->status === 'open')
                                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                        @elseif ($ticket->status === 'in_progress')
                                            <span class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-100">In Progress</span>
                                        @elseif ($ticket->status === 'resolved')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                        @endif
                                    </td>
                                    <td class="max-w-xs px-4 py-4">
                                        @if ($ticket->assignedTechnician)
                                            <p class="truncate font-medium text-slate-700">{{ $ticket->assignedTechnician->name }}</p>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">
                                        @if ($ticket->email_received_at)
                                            <p class="font-medium">{{ $ticket->email_received_at->format('d M Y, H:i') }}</p>
                                            <p class="mt-1 text-xs text-slate-400">{{ $ticket->email_received_at->diffForHumans() }}</p>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                                            Detail
                                            <x-heroicon-o-arrow-right class="h-4 w-4" />
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-14 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                            <x-heroicon-o-envelope class="h-6 w-6" />
                                        </div>
                                        <h2 class="mt-4 text-sm font-semibold text-slate-700">No email tickets yet</h2>
                                        <p class="mt-1 text-sm text-slate-500">Imported email requests will appear in this queue.</p>
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
