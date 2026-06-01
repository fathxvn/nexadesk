<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">Ticket #{{ $ticket->id }}</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800">Edit Ticket</h1>
                    <p class="mt-2 text-sm text-slate-500">Update the request details and priority.</p>
                </div>

                <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-indigo-700">
                    Cancel
                </a>
            </div>

            <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf
                @method('PUT')

                <div class="space-y-6 p-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Title</label>
                        <input
                            type="text"
                            name="title"
                            class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('title', $ticket->title) }}"
                        >
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea
                            name="description"
                            class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            rows="7"
                        >{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Priority</label>
                        <select name="priority" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="low" {{ old('priority', $ticket->priority) === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $ticket->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $ticket->priority) === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50/80 px-6 py-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                        Cancel
                    </a>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <x-heroicon-o-check class="h-4 w-4" />
                        Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
