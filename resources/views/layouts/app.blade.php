<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div
            x-data="{ mobileMenuOpen: false, sidebarCollapsed: false }"
            class="min-h-screen bg-slate-50"
        >
            @include('layouts.navigation')

            @php
                $notification = session('notification');

                if (! $notification && session('success')) {
                    $notification = [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => session('success'),
                    ];
                }

                if (! $notification && session('error')) {
                    $notification = [
                        'type' => 'danger',
                        'title' => 'Error',
                        'message' => session('error'),
                    ];
                }

                $notificationStyles = [
                    'success' => [
                        'icon' => 'heroicon-o-check-circle',
                        'accent' => 'bg-emerald-50 text-emerald-600',
                    ],
                    'info' => [
                        'icon' => 'heroicon-o-information-circle',
                        'accent' => 'bg-blue-50 text-blue-600',
                    ],
                    'warning' => [
                        'icon' => 'heroicon-o-exclamation-triangle',
                        'accent' => 'bg-amber-50 text-amber-600',
                    ],
                    'danger' => [
                        'icon' => 'heroicon-o-x-circle',
                        'accent' => 'bg-red-50 text-red-600',
                    ],
                ];

                $notificationType = $notification['type'] ?? 'info';
                $notificationStyle = $notificationStyles[$notificationType] ?? $notificationStyles['info'];
            @endphp

            @if ($notification)
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3000)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed right-4 top-4 z-50 w-full max-w-sm sm:right-6 sm:top-6"
                    role="status"
                >
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-lg">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $notificationStyle['accent'] }}">
                                <x-dynamic-component :component="$notificationStyle['icon']" class="h-5 w-5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-800">{{ $notification['title'] ?? 'Notification' }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $notification['message'] ?? '' }}</p>
                            </div>

                            <button
                                type="button"
                                @click="show = false"
                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                aria-label="Dismiss notification"
                            >
                                <x-heroicon-o-x-mark class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="transition-all duration-300 ease-in-out"
                :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
            >
                <!-- Page Heading -->
                @isset($header)
                    <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                        <div class="px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="min-h-screen">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
