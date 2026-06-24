<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-cyan-50 via-white to-violet-50">

        @php
            ...
        @endphp

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <span
                        class="inline-flex items-center rounded-full bg-violet-100 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-violet-700">
                        Support Center
                    </span>

                    <h1
                        class="mt-4 text-4xl font-bold tracking-tight text-slate-900 lg:text-5xl">
                        My Tickets
                    </h1>

                    <p class="mt-3 text-lg text-slate-600">
                        Track and manage all your support requests from one place.
                    </p>
                </div>

                <a
                    href="{{ route('tickets.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-violet-600 px-6 py-3 font-semibold text-white shadow-lg transition hover:scale-105 hover:shadow-xl">

                    <x-heroicon-o-plus-circle class="h-5 w-5" />

                    Create Ticket
                </a>

            </div>