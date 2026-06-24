<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-600">New Request</p>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-800">Create Ticket</h1>
                    <p class="mt-2 text-sm text-slate-500">Share the issue details so the support team can help quickly.</p>
                </div>

                <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-indigo-700">
                    Cancel
                </a>
            </div>

            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                @csrf

                <div class="space-y-6 p-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Title</label>
                        <input
                            type="text"
                            name="title"
                            class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('title') }}"
                            placeholder="Short summary of the issue"
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
                            placeholder="Describe what happened, what you expected, and any useful context."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Category</label>
                        <select name="category" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="network" {{ old('category', 'other') === 'network' ? 'selected' : '' }}>Network</option>
                            <option value="hardware" {{ old('category', 'other') === 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="software" {{ old('category', 'other') === 'software' ? 'selected' : '' }}>Software</option>
                            <option value="email" {{ old('category', 'other') === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="account_access" {{ old('category', 'other') === 'account_access' ? 'selected' : '' }}>Account Access</option>
                            <option value="printer" {{ old('category', 'other') === 'printer' ? 'selected' : '' }}>Printer</option>
                            <option value="other" {{ old('category', 'other') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Priority</label>
                        <select name="priority" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Department</label>
                        <select name="department_id" class="mt-1 w-full rounded-xl border-slate-300 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Department</option>

                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('department_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Attachment</label>
                        <input
                            type="file"
                            name="attachment"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="mt-1 block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 shadow-sm file:mr-4 file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <p class="mt-2 text-xs text-slate-500">Upload a JPG, PNG, or PDF up to 5 MB.</p>
                        @error('attachment')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50/80 px-6 py-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                        Cancel
                    </a>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <x-heroicon-o-check class="h-4 w-4" />
                        Save Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
