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

            $category = $ticket->category ?? 'other';
            $attachmentUrl = $ticket->attachment_path ? \Illuminate\Support\Facades\Storage::url($ticket->attachment_path) : null;
            $attachmentName = $ticket->attachment_path ? basename($ticket->attachment_path) : null;
            $attachmentExtension = $ticket->attachment_path ? strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION)) : null;
            $attachmentIsImage = in_array($attachmentExtension, ['jpg', 'jpeg', 'png'], true);
        @endphp

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            {{-- ========================================= --}}
            {{-- SECTION: HEADER --}}
            {{-- ========================================= --}}
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-medium text-indigo-600">Ticket #{{ $ticket->id }}</p>

                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $categoryClasses[$category] ?? $categoryClasses['other'] }}">
                            {{ $categoryLabels[$category] ?? $categoryLabels['other'] }}
                        </span>

                        @if ($ticket->priority === 'high')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                        @elseif ($ticket->priority === 'medium')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                        @endif

                        @if ($ticket->status === 'open')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                        @elseif ($ticket->status === 'in_progress')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">In Progress</span>
                        @elseif ($ticket->status === 'resolved')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                        @endif

                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $ticket->slaBadgeClasses() }}">
                            SLA {{ $ticket->slaLabel() }}
                        </span>
                    </div>

                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800 sm:text-3xl">
                        {{ $ticket->title }}
                    </h1>

                    <p class="mt-2 flex flex-wrap items-center gap-x-1 gap-y-1 text-sm text-slate-500">
                        <span>Reported by</span>
                        <span class="max-w-full truncate font-medium text-slate-600 sm:max-w-xs">{{ $ticket->user->name ?? 'Unknown User' }}</span>
                        <span>&middot;</span>
                        <span class="whitespace-nowrap">{{ $ticket->created_at->diffForHumans() }}</span>
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
                    {{-- ========================================= --}}
                    {{-- SECTION: TICKET DESCRIPTION --}}
                    {{-- ========================================= --}}
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

                    {{-- ========================================= --}}
                    {{-- SECTION: EMAIL SOURCE --}}
                    {{-- ========================================= --}}
                    @if ($ticket->source === 'email')
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                    <x-heroicon-o-envelope class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Email Source</h2>
                                    <p class="text-sm text-slate-500">Original email message submitted by the sender.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">From</p>
                                    <p class="mt-3 break-words text-sm font-medium text-slate-700">
                                        {{ $ticket->email_from_name ?: 'Unknown Sender' }}
                                    </p>
                                    @if ($ticket->email_from)
                                        <p class="mt-1 break-all text-xs text-slate-500">{{ $ticket->email_from }}</p>
                                    @endif
                                </div>

                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Received At</p>
                                    <p class="mt-3 text-sm font-medium text-slate-700">
                                        {{ $ticket->email_received_at?->format('d M Y, H:i') ?? '-' }}
                                    </p>
                                    @if ($ticket->email_received_at)
                                        <p class="mt-1 text-xs text-slate-500">{{ $ticket->email_received_at->diffForHumans() }}</p>
                                    @endif
                                </div>

                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 md:col-span-2">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Subject</p>
                                    <p class="mt-3 break-words text-sm font-medium text-slate-700">
                                        {{ $ticket->email_subject ?? $ticket->title }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 md:col-span-2">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Message Body</p>
                                    <div class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $ticket->email_body ?? $ticket->description }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ========================================= --}}
                    {{-- SECTION: COMPOSE EMAIL REPLY --}}
                    {{-- ========================================= --}}
                    @if (auth()->user()->isStaff() && $ticket->source === 'email')
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <x-heroicon-o-paper-airplane class="h-5 w-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-slate-800">Compose Email Reply</h2>
                                    <p class="text-sm text-slate-500">Send a reply to the original email sender.</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('staff.tickets.email-reply.store', $ticket) }}" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="email-reply-to" class="block text-sm font-medium text-slate-700">To</label>
                                    <input
                                        id="email-reply-to"
                                        type="email"
                                        value="{{ $ticket->email_from }}"
                                        readonly
                                        class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-500 shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label for="email-reply-subject" class="block text-sm font-medium text-slate-700">Subject</label>
                                    <input
                                        id="email-reply-subject"
                                        type="text"
                                        value="Re: [NexaDesk #{{ $ticket->id }}] {{ $ticket->email_subject ?: $ticket->title }}"
                                        readonly
                                        class="mt-2 w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-500 shadow-sm"
                                    >
                                </div>

                                <div>
                                    <label for="email-reply-message" class="block text-sm font-medium text-slate-700">Message</label>
                                    <textarea
                                        id="email-reply-message"
                                        name="message"
                                        rows="6"
                                        required
                                        maxlength="10000"
                                        placeholder="Write your reply to the sender..."
                                        class="mt-2 w-full resize-y rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >{{ old('message') }}</textarea>

                                    @error('message', 'emailReply')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if (blank($ticket->email_from))
                                    <div class="rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                                        This ticket does not have a destination email address.
                                    </div>
                                @endif

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                >
                                    <x-heroicon-o-paper-airplane class="h-4 w-4" />
                                    Send Email Reply
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- ========================================= --}}
                    {{-- SECTION: ATTACHMENT --}}
                    {{-- ========================================= --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <x-heroicon-o-paper-clip class="h-5 w-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">Attachment</h2>
                                <p class="text-sm text-slate-500">Supporting file submitted with this ticket.</p>
                            </div>
                        </div>

                        @if ($ticket->attachment_path)
                            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                @if ($attachmentIsImage)
                                    <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener" class="block bg-white">
                                        <img src="{{ $attachmentUrl }}" alt="Ticket attachment preview" class="max-h-80 w-full object-contain">
                                    </a>
                                @else
                                    <div class="flex items-center gap-4 bg-white p-5">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                                            <x-heroicon-o-document-text class="h-6 w-6" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-800">{{ $attachmentName }}</p>
                                            <p class="mt-1 text-xs text-slate-500">PDF attachment</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex flex-col gap-3 border-t border-slate-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="truncate text-sm text-slate-500">{{ $attachmentName }}</p>
                                    <div class="flex gap-2">
                                        <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                                            View
                                        </a>
                                        <a href="{{ $attachmentUrl }}" download class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-400">
                                    <x-heroicon-o-paper-clip class="h-5 w-5" />
                                </div>
                                <p class="mt-3 text-sm font-medium text-slate-700">No attachment</p>
                                <p class="mt-1 text-sm text-slate-500">This ticket does not include a supporting file.</p>
                            </div>
                        @endif
                    </div>

                    {{-- ========================================= --}}
                    {{-- SECTION: TICKET METADATA --}}
                    {{-- ========================================= --}}
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
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Department</p>
                                <div class="mt-3">
                                    @if ($ticket->department)
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-cyan-50 px-3 py-1 text-xs font-medium text-cyan-700 ring-1 ring-inset ring-cyan-100">
                                            {{ $ticket->department->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">
                                            No Department
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Priority</p>
                                <div class="mt-3">
                                    @if ($ticket->priority === 'high')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-100">High</span>
                                    @elseif ($ticket->priority === 'medium')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Medium</span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Low</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Status</p>
                                <div class="mt-3">
                                    @if ($ticket->status === 'open')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-100">Open</span>
                                    @elseif ($ticket->status === 'in_progress')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">In Progress</span>
                                    @elseif ($ticket->status === 'resolved')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">Resolved</span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">Closed</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">SLA Status</p>
                                <div class="mt-3">
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $ticket->slaBadgeClasses() }}">
                                        {{ $ticket->slaLabel() }}
                                    </span>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">SLA Due</p>
                                @if ($ticket->sla_due_at)
                                    <p class="mt-3 whitespace-nowrap text-sm font-medium text-slate-700">{{ $ticket->sla_due_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 whitespace-nowrap text-xs text-slate-500">{{ $ticket->sla_due_at->diffForHumans() }}</p>
                                @else
                                    <p class="mt-3 text-sm font-medium text-slate-500">Not set</p>
                                @endif
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">SLA Resolved</p>
                                @if ($ticket->sla_resolved_at)
                                    <p class="mt-3 whitespace-nowrap text-sm font-medium text-slate-700">{{ $ticket->sla_resolved_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 whitespace-nowrap text-xs text-slate-500">{{ $ticket->sla_resolved_at->diffForHumans() }}</p>
                                @else
                                    <p class="mt-3 text-sm font-medium text-slate-500">Open timer</p>
                                @endif
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">SLA Breach</p>
                                @if ($ticket->sla_breached_at)
                                    <p class="mt-3 whitespace-nowrap text-sm font-medium text-red-700">{{ $ticket->sla_breached_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 whitespace-nowrap text-xs text-slate-500">{{ $ticket->sla_breached_at->diffForHumans() }}</p>
                                @elseif ($ticket->isSlaOverdue())
                                    <p class="mt-3 text-sm font-medium text-red-700">Overdue now</p>
                                    <p class="mt-1 text-xs text-slate-500">Breach will be stored on next status update.</p>
                                @else
                                    <p class="mt-3 text-sm font-medium text-slate-500">No breach</p>
                                @endif
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Reporter</p>
                                <div class="mt-3 flex min-w-0 items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600">
                                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-slate-700">{{ $ticket->user->name ?? 'Unknown User' }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $ticket->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Assigned Technician</p>
                                <div class="mt-3">
                                    @if ($ticket->assignedTechnician)
                                        <div class="flex min-w-0 items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700">
                                                {{ strtoupper(substr($ticket->assignedTechnician->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-slate-700">{{ $ticket->assignedTechnician->name }}</p>
                                                <p class="text-xs text-slate-500">{{ ucfirst($ticket->assignedTechnician->role) }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-slate-50 px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-inset ring-slate-200">Unassigned</span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Created</p>
                                <p class="mt-3 whitespace-nowrap text-sm font-medium text-slate-700">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 whitespace-nowrap text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</p>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Last Updated</p>
                                <p class="mt-3 whitespace-nowrap text-sm font-medium text-slate-700">{{ $ticket->updated_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 whitespace-nowrap text-xs text-slate-500">{{ $ticket->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ========================================= --}}
                    {{-- SECTION: ASSIGNED TECHNICIAN --}}
                    {{-- ========================================= --}}
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

                    {{-- ========================================= --}}
                    {{-- SECTION: INTERNAL NOTES --}}
                    {{-- ========================================= --}}
                    @if (auth()->user()->isStaff())
                        <div class="overflow-hidden rounded-2xl border border-amber-200 bg-white shadow-sm">
                            <div class="border-b border-amber-100 bg-amber-50/60 px-6 py-5">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-amber-600 shadow-sm ring-1 ring-amber-100">
                                            <x-heroicon-o-lock-closed class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h2 class="text-base font-semibold text-slate-800">Internal Notes</h2>
                                                <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-1 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-100">Staff only</span>
                                            </div>
                                            <p class="mt-1 text-sm text-slate-500">Private context for admins and technicians.</p>
                                        </div>
                                    </div>

                                    <span class="text-sm text-slate-500">{{ $ticket->internalNotes->count() }} notes</span>
                                </div>
                            </div>

                            <div class="space-y-4 px-6 py-5">
                                @forelse ($ticket->internalNotes->sortByDesc('created_at') as $note)
                                    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-4">
                                        <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-xs font-semibold text-amber-700 ring-1 ring-amber-100">
                                                    {{ strtoupper(substr($note->user->name ?? 'S', 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm font-semibold text-slate-800">{{ $note->user->name ?? 'Staff' }}</p>
                                                    <p class="text-xs text-slate-500">{{ ucfirst($note->user->role ?? 'staff') }}</p>
                                                </div>
                                            </div>

                                            <span class="text-xs text-slate-400">{{ $note->created_at->diffForHumans() }}</span>
                                        </div>

                                        <p class="whitespace-pre-line text-sm leading-6 text-slate-700">{{ $note->body }}</p>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-amber-200 bg-amber-50/40 p-6 text-center">
                                        <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-white text-amber-500">
                                            <x-heroicon-o-lock-closed class="h-5 w-5" />
                                        </div>
                                        <p class="mt-3 text-sm font-medium text-slate-700">No internal notes yet</p>
                                        <p class="mt-1 text-sm text-slate-500">Private staff notes will appear here.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-t border-amber-100 bg-slate-50/80 p-6">
                                <form method="POST" action="{{ route('staff.tickets.internal-notes.store', $ticket) }}">
                                    @csrf

                                    <label class="mb-2 flex items-center gap-2 text-sm font-medium text-slate-700">
                                        Add Internal Note
                                        <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-100">Staff only</span>
                                    </label>

                                    <textarea
                                        name="body"
                                        rows="4"
                                        required
                                        maxlength="2000"
                                        placeholder="Add private troubleshooting context, handoff notes, or next steps..."
                                        class="w-full resize-none rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >{{ old('body') }}</textarea>

                                    @error('body')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                                            <x-heroicon-o-lock-closed class="h-4 w-4" />
                                            Save Internal Note
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- ========================================= --}}
                    {{-- SECTION: ACTIVITY TIMELINE --}}
                    {{-- ========================================= --}}
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

                    {{-- ========================================= --}}
                    {{-- SECTION: STAFF ACTIONS --}}
                    {{-- ========================================= --}}
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
                    {{-- ========================================= --}}
                    {{-- SECTION: CONVERSATION / COMMENTS --}}
                    {{-- ========================================= --}}
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
