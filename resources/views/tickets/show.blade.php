<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-medium text-indigo-600">Ticket #{{ $ticket->id }}</p>

                        @if ($ticket->priority === 'high')
                            <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                        @elseif ($ticket->priority === 'medium')
                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                        @endif

                        @if ($ticket->status === 'open')
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                        @elseif ($ticket->status === 'in_progress')
                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">In Progress</span>
                        @elseif ($ticket->status === 'resolved')
                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                        @else
                            <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                        @endif
                    </div>

                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">
                        {{ $ticket->title }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Reported by {{ $ticket->user->name ?? 'Unknown User' }} &middot; {{ $ticket->created_at->diffForHumans() }}
                    </p>
                </div>

                <a
                    href="{{ auth()->user()->isStaff() ? route('staff.tickets.index') : route('tickets.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-indigo-700"
                >
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    Back
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <div class="space-y-6 xl:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <x-heroicon-o-document-text class="h-5 w-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">Ticket Description</h2>
                                <p class="text-sm text-slate-500">Original request details.</p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="whitespace-pre-line text-sm leading-6 text-slate-700">{{ $ticket->description }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <x-heroicon-o-information-circle class="h-5 w-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">Ticket Metadata</h2>
                                <p class="text-sm text-slate-500">Reporter, ownership, priority, and timing.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Priority</p>
                                <div class="mt-3">
                                    @if ($ticket->priority === 'high')
                                        <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                                    @elseif ($ticket->priority === 'medium')
                                        <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</p>
                                <div class="mt-3">
                                    @if ($ticket->status === 'open')
                                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                    @elseif ($ticket->status === 'in_progress')
                                        <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">In Progress</span>
                                    @elseif ($ticket->status === 'resolved')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Reporter</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $ticket->user->name ?? 'Unknown User' }}</p>
                                        <p class="text-xs text-slate-500">{{ $ticket->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Assigned Technician</p>
                                <div class="mt-3">
                                    @if ($ticket->assignedTechnician)
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700">
                                                {{ strtoupper(substr($ticket->assignedTechnician->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-slate-700">{{ $ticket->assignedTechnician->name }}</p>
                                                <p class="text-xs text-slate-500">{{ ucfirst($ticket->assignedTechnician->role) }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">Unassigned</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Created</p>
                                <p class="mt-3 text-sm font-medium text-slate-700">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</p>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Last Updated</p>
                                <p class="mt-3 text-sm font-medium text-slate-700">{{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $ticket->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->isStaff())
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <x-heroicon-o-user-plus class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Assign Technician</h2>
                                    <p class="text-sm text-slate-500">Route this ticket to a staff member.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('staff.tickets.assign', $ticket) }}">
                                @csrf
                                @method('PATCH')

                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <select name="assigned_to_user_id" class="w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-md">
                                        @foreach ($technicians as $technician)
                                            <option value="{{ $technician->id }}" {{ $ticket->assigned_to_user_id == $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }} - {{ ucfirst($technician->role) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                        <x-heroicon-o-check class="h-4 w-4" />
                                        Assign
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5">
                            <h3 class="text-base font-semibold text-slate-800">Activity Timeline</h3>
                            <p class="mt-1 text-sm text-slate-500">Riwayat perubahan pada ticket ini.</p>
                        </div>

                        <div class="space-y-0">
                            @forelse($ticket->activities->sortByDesc('created_at') as $activity)
                                <div class="relative flex gap-4 pb-6 last:pb-0">
                                    <div class="flex flex-col items-center">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700">
                                            {{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}
                                        </div>

                                        @if(! $loop->last)
                                            <div class="mt-2 h-full w-px bg-slate-200"></div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1 rounded-2xl bg-slate-50 p-4">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                            <p class="text-sm font-medium text-slate-700">{{ $activity->description }}</p>
                                            <span class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">By {{ $activity->user->name ?? 'System' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-500">Belum ada aktivitas pada ticket ini.</div>
                            @endforelse
                        </div>
                    </div>

                    @if (auth()->user()->isStaff())
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                    <x-heroicon-o-adjustments-horizontal class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Staff Action</h2>
                                    <p class="text-sm text-slate-500">Update the ticket lifecycle status.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('staff.tickets.updateStatus', $ticket) }}">
                                @csrf
                                @method('PATCH')

                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <select name="status" class="w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs">
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>

                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                                        <x-heroicon-o-arrow-path class="h-4 w-4" />
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($ticket->user_id === auth()->id())
                        <div x-data="{ deleteModalOpen: false }" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                    <x-heroicon-o-wrench-screwdriver class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Ticket Actions</h2>
                                    <p class="text-sm text-slate-500">Edit or remove your submitted request.</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                    <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    Edit Ticket
                                </a>

                                <form x-ref="deleteForm" method="POST" action="{{ route('tickets.destroy', $ticket) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" @click="deleteModalOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                        Delete Ticket
                                    </button>
                                </form>
                            </div>

                            <div
                                x-show="deleteModalOpen"
                                x-transition.opacity
                                class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
                                aria-modal="true"
                                role="dialog"
                            >
                                <button
                                    type="button"
                                    class="absolute inset-0 h-full w-full bg-slate-950/40"
                                    @click="deleteModalOpen = false"
                                    aria-label="Close delete confirmation"
                                ></button>

                                <div
                                    x-show="deleteModalOpen"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="translate-y-2 scale-95 opacity-0"
                                    x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                                    x-transition:leave-end="translate-y-2 scale-95 opacity-0"
                                    class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-lg"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                                            <x-heroicon-o-trash class="h-5 w-5" />
                                        </div>

                                        <div>
                                            <h3 class="text-base font-semibold text-slate-800">Delete ticket?</h3>
                                            <p class="mt-2 text-sm text-slate-500">This action cannot be undone.</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                        <button
                                            type="button"
                                            @click="deleteModalOpen = false"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-800"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="button"
                                            @click="$refs.deleteForm.submit()"
                                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700"
                                        >
                                            Confirm delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="xl:col-span-1">
                    <div class="sticky top-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-5 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Conversation</h2>
                                    <p class="mt-1 text-sm text-slate-500">{{ $ticket->comments->count() }} comments</p>
                                </div>

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <x-heroicon-o-chat-bubble-left-right class="h-5 w-5" />
                                </div>
                            </div>
                        </div>

                        <div class="max-h-[520px] space-y-4 overflow-y-auto px-5 py-4">
                            @forelse ($ticket->comments as $comment)
                                <div class="flex gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                                            <div class="mb-2 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800">{{ $comment->user->name ?? 'Unknown User' }}</p>
                                                    <p class="text-xs text-slate-500">{{ ucfirst($comment->user->role ?? 'user') }}</p>
                                                </div>

                                                <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>

                                            <p class="whitespace-pre-line text-sm leading-6 text-slate-700">{{ $comment->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center">
                                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                        <x-heroicon-o-chat-bubble-left class="h-6 w-6" />
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">No conversation yet</p>
                                    <p class="mt-1 text-xs text-slate-500">Add the first reply to start the discussion.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="border-t border-slate-200 bg-slate-50/80 p-5">
                            <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                                @csrf

                                <label class="mb-2 block text-sm font-medium text-slate-700">Add Reply</label>

                                <textarea
                                    name="message"
                                    rows="4"
                                    required
                                    placeholder="Write a reply..."
                                    class="w-full resize-none rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>

                                @error('message')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <button type="submit" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                    <x-heroicon-o-paper-airplane class="h-4 w-4" />
                                    Send Reply
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
