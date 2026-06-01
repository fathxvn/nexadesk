<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-sm font-medium text-indigo-600">Account recovery</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800">Reset your password</h1>
        <p class="mt-2 text-sm text-slate-500">
            Enter your email and we will send you a password reset link.
        </p>
    </div>

    <x-auth-session-status class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700" />
            <x-text-input id="email" class="mt-1 block w-full rounded-xl border-slate-300 text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            {{ __('Email Password Reset Link') }}
        </button>

        <p class="text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 transition hover:text-indigo-700">Back to login</a>
        </p>
    </form>
</x-guest-layout>
