<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-sm font-medium text-indigo-600">Welcome back</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800">Sign in to NexaDesk</h1>
        <p class="mt-2 text-sm text-slate-500">Manage support requests from your helpdesk workspace.</p>
    </div>

    <x-auth-session-status class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-slate-700" />

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 transition hover:text-indigo-700" href="{{ route('password.request') }}">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            {{ __('Log in') }}
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-500">
                New to NexaDesk?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 transition hover:text-indigo-700">{{ __('Create an account') }}</a>
            </p>
        @endif
    </form>
</x-guest-layout>
