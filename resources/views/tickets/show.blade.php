<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">NexaDesk Ticket</p>

                    <div class="flex items-center gap-3 mt-1">
                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $ticket->title }}
                        </h1>

                        <span class="text-sm text-slate-400">
                            #{{ $ticket->id }}
                        </span>
                    </div>

                    <p class="text-slate-500 mt-2">
                        Reported by {{ $ticket->user->name ?? 'Unknown User' }}
                        · {{ $ticket->created_at->diffForHumans() }}
                    </p>
                </div>

                <a href="{{ auth()->user()->isStaff() ? route('staff.tickets.index') : route('tickets.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 rounded-xl text-sm text-slate-600 hover:bg-white">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Back
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-heroicon-o-document-text class="w-5 h-5 text-slate-400" />
                            <h2 class="text-lg font-semibold text-slate-900">
                                Ticket Description
                            </h2>
                        </div>

                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $ticket->description }}
                        </p>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-5">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-slate-400" />
                            <h2 class="text-lg font-semibold text-slate-900">
                                Ticket Metadata
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Priority</p>

                                @if ($ticket->priority === 'high')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        High
                                    </span>
                                @elseif ($ticket->priority === 'medium')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Medium
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        Low
                                    </span>
                                @endif
                            </div>

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Status</p>

                                @if ($ticket->status === 'open')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        Open
                                    </span>
                                @elseif ($ticket->status === 'in_progress')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        In Progress
                                    </span>
                                @elseif ($ticket->status === 'resolved')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        Resolved
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        Closed
                                    </span>
                                @endif
                            </div>

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Reporter</p>

                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold">
                                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ $ticket->user->name ?? 'Unknown User' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $ticket->user->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Assigned Technician</p>

                                @if ($ticket->assignedTechnician)
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-semibold">
                                            {{ strtoupper(substr($ticket->assignedTechnician->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-medium text-slate-900">
                                                {{ $ticket->assignedTechnician->name }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ ucfirst($ticket->assignedTechnician->role) }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Unassigned
                                    </span>
                                @endif
                            </div>

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Created</p>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $ticket->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="border border-slate-100 rounded-xl p-4">
                                <p class="text-xs text-slate-500 mb-2">Last Updated</p>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $ticket->updated_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </p>
                            </div>

                        </div>
                    </div>

                    @if (auth()->user()->isStaff())
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <x-heroicon-o-user-plus class="w-5 h-5 text-slate-400" />
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Assign Technician
                                </h2>
                            </div>

                            <form method="POST" action="{{ route('staff.tickets.assign', $ticket) }}">
                                @csrf
                                @method('PATCH')

                                <div class="flex flex-wrap gap-3 items-center">
                                    <select name="assigned_to_user_id" class="rounded-xl border-slate-300 text-sm">
                                        @foreach ($technicians as $technician)
                                            <option value="{{ $technician->id }}"
                                                {{ $ticket->assigned_to_user_id == $technician->id ? 'selected' : '' }}>
                                                {{ $technician->name }} - {{ ucfirst($technician->role) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                                        <x-heroicon-o-check class="w-4 h-4" />
                                        Assign
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-5">
        <h3 class="text-lg font-semibold text-slate-900">
            Activity Timeline
        </h3>
        <p class="text-sm text-slate-500">
            Riwayat perubahan pada ticket ini.
        </p>
    </div>

    @forelse($ticket->activities->sortByDesc('created_at') as $activity)
        <div class="relative flex gap-4 pb-6 last:pb-0">
            <div class="flex flex-col items-center">
                <div class="h-9 w-9 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold">
                    {{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}
                </div>

                @if(! $loop->last)
                    <div class="mt-2 h-full w-px bg-slate-200"></div>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-900">
                        {{ $activity->description }}
                    </p>

                    <span class="text-xs text-slate-400 whitespace-nowrap">
                        {{ $activity->created_at->diffForHumans() }}
                    </span>
                </div>

                <p class="mt-1 text-xs text-slate-500">
                    By {{ $activity->user->name ?? 'System' }}
                </p>
            </div>
        </div>
    @empty
        <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
            Belum ada aktivitas pada ticket ini.
        </div>
    @endforelse
</div>

                    @if (auth()->user()->isStaff())
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <x-heroicon-o-adjustments-horizontal class="w-5 h-5 text-slate-400" />
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Staff Action
                                </h2>
                            </div>

                            <form method="POST" action="{{ route('staff.tickets.updateStatus', $ticket) }}">
                                @csrf
                                @method('PATCH')

                                <div class="flex flex-wrap gap-3 items-center">
                                    <select name="status" class="rounded-xl border-slate-300 text-sm">
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>
                                            Open
                                        </option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>
                                            In Progress
                                        </option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>
                                            Resolved
                                        </option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                                            Closed
                                        </option>
                                    </select>

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-medium hover:bg-slate-800">
                                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($ticket->user_id === auth()->id())
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-slate-400" />
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Ticket Actions
                                </h2>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('tickets.edit', $ticket) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-medium hover:bg-slate-800">
                                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    Edit Ticket
                                </a>

                                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus ticket ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        Delete Ticket
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden sticky top-6">

                        <div class="px-5 py-4 border-b border-slate-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">
                                        Conversation
                                    </h2>
                                    <p class="text-sm text-slate-500">
                                        {{ $ticket->comments->count() }} comments
                                    </p>
                                </div>

                                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-slate-400" />
                            </div>
                        </div>

                        <div class="max-h-[520px] overflow-y-auto px-5 py-4 space-y-4">
                            @forelse ($ticket->comments as $comment)
                                <div class="flex gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                    </div>

                                    <div class="flex-1">
                                        <div class="bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3">
                                            <div class="flex items-center justify-between gap-3 mb-1">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $comment->user->name ?? 'Unknown User' }}
                                                    </p>

                                                    <p class="text-xs text-slate-500">
                                                        {{ ucfirst($comment->user->role ?? 'user') }}
                                                    </p>
                                                </div>

                                                <span class="text-xs text-slate-400 whitespace-nowrap">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                                {{ $comment->message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                        <x-heroicon-o-chat-bubble-left class="w-6 h-6 text-slate-400" />
                                    </div>

                                    <p class="text-sm font-medium text-slate-700">
                                        No conversation yet.
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Add the first comment to start discussion.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        <div class="border-t border-slate-100 p-5">
                            <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                                @csrf

                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Add Reply
                                </label>

                                <textarea name="message"
                                          rows="4"
                                          required
                                          placeholder="Write a reply..."
                                          class="w-full rounded-xl border-slate-300 text-sm resize-none focus:border-slate-500 focus:ring-slate-500"></textarea>

                                @error('message')
                                    <p class="text-sm text-red-600 mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button type="submit"
                                        class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-medium hover:bg-slate-800">
                                    <x-heroicon-o-paper-airplane class="w-4 h-4" />
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