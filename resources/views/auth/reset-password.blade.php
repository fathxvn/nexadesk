<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-sm font-medium text-indigo-600">Secure reset</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800">Choose a new password</h1>
        <p class="mt-2 text-sm text-slate-500">Create a fresh password for your NexaDesk account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700" />
            <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-700" />

            <x-text-input id="password_confirmation" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            {{ __('Reset Password') }}
        </button>
    </form>
</x-guest-layout>
