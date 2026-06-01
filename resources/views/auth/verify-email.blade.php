<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-sm font-medium text-indigo-600">Verify email</p>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-800">Check your inbox</h1>
        <p class="mt-2 text-sm text-slate-500">
            {{ __('Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-700">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
