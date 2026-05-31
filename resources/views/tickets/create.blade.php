<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-6">
        <h1 class="text-2xl font-bold mb-6">Create Ticket</h1>

        <form method="POST" action="{{ route('tickets.store') }}"
              class="bg-white rounded-xl shadow p-6 space-y-5">
            @csrf

            <div>
                <label class="block font-medium mb-1">Title</label>
                <input type="text" name="title"
                       class="w-full border-gray-300 rounded-lg"
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description"
                          class="w-full border-gray-300 rounded-lg"
                          rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Priority</label>
                <select name="priority" class="w-full border-gray-300 rounded-lg">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
                @error('priority')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Save Ticket
                </button>

                <a href="{{ route('tickets.index') }}"
                   class="px-4 py-2 rounded-lg border">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>