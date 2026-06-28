<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NexaDesk - Helpdesk Management Platform</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gradient-to-b from-indigo-50 via-white to-slate-50 text-slate-950 antialiased">
        <div class="min-h-screen overflow-hidden">
            <header class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur">
                <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:px-8" aria-label="Main navigation">
                    <a href="#home" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50">
                            <x-heroicon-o-chat-bubble-left-right class="h-6 w-6 text-indigo-600" />
                        </span>
                        <span class="text-lg font-bold text-slate-950">NexaDesk</span>
                    </a>

                    <div class="hidden items-center gap-8 md:flex">
                        <a href="#home" class="text-sm font-medium text-slate-600 transition hover:text-slate-950">Home</a>
                        <a href="#features" class="text-sm font-medium text-slate-600 transition hover:text-slate-950">Features</a>
                        <a href="#workflow" class="text-sm font-medium text-slate-600 transition hover:text-slate-950">Workflow</a>
                        <a href="#faq" class="text-sm font-medium text-slate-600 transition hover:text-slate-950">FAQ</a>
                    </div>

                    <div class="flex items-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="hidden text-sm font-semibold text-slate-700 transition hover:text-slate-950 sm:inline-flex">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-slate-700 transition hover:text-slate-950 sm:inline-flex">Login</a>
                            @endauth
                        @endif

                        @if (Route::has('tickets.create'))
                            <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">Create Ticket</a>
                        @endif
                    </div>
                </nav>
            </header>

            <main>
                <section id="home" class="relative overflow-hidden py-24 lg:py-28">
                    <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-indigo-300 opacity-30 blur-3xl"></div>
                    <div class="pointer-events-none absolute -right-24 top-40 h-80 w-80 rounded-full bg-cyan-300 opacity-30 blur-3xl"></div>

                    <div class="mx-auto grid max-w-7xl items-center gap-16 px-6 lg:grid-cols-2 lg:px-8">
                        <div class="hero-content relative z-10">
                            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">HELPDESK MANAGEMENT PLATFORM</p>
                            <h1 class="mt-6 text-5xl font-extrabold tracking-tight text-slate-950 lg:text-6xl">Solusi Cepat untuk<br class="hidden sm:block">Setiap Masalah IT</h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">NexaDesk membantu organisasi mengelola laporan IT, memantau SLA, mengotomatisasi email ticket, dan mempercepat proses penyelesaian masalah dalam satu platform terpusat.</p>

                            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                                @if (Route::has('tickets.create'))
                                    <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">Create Ticket</a>
                                @endif
                                <a href="#features" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-900 transition hover:border-slate-400 hover:bg-slate-50">Explore Features</a>
                            </div>

                            <div class="mt-10 flex flex-col gap-4 text-sm font-semibold text-slate-700 sm:flex-row sm:flex-wrap">
                                <div class="flex items-center gap-2"><x-heroicon-o-envelope class="h-6 w-6 text-indigo-600" /><span>Mail-to-Ticket</span></div>
                                <div class="flex items-center gap-2"><x-heroicon-o-clock class="h-6 w-6 text-indigo-600" /><span>SLA Tracking</span></div>
                                <div class="flex items-center gap-2"><x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" /><span>Assigned Technician</span></div>
                            </div>
                        </div>

                        <div class="hero-image relative z-10 mx-auto w-full max-w-2xl lg:mx-0">
                            <div class="dashboard-preview relative rounded-3xl border border-slate-200 bg-white p-3 shadow-2xl shadow-slate-900/20">
                                <div class="flex items-center gap-2 border-b border-slate-200 px-4 py-3"><span class="h-3 w-3 rounded-full bg-rose-400"></span><span class="h-3 w-3 rounded-full bg-amber-400"></span><span class="h-3 w-3 rounded-full bg-emerald-400"></span></div>
                                <img src="{{ asset('docs/section-1.png') }}" alt="NexaDesk dashboard preview" class="aspect-[16/10] w-full rounded-2xl object-cover object-top">
                            </div>

                            <div class="absolute -left-3 top-10 hidden rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-xl shadow-slate-900/10 sm:block">
                                <div class="flex items-center gap-3"><x-heroicon-o-envelope class="h-6 w-6 text-indigo-600" /><div><p class="text-sm font-semibold text-slate-900">Email Tickets</p><p class="text-xs font-medium text-slate-500">18 Active</p></div></div>
                            </div>
                            <div class="absolute -right-4 bottom-20 hidden rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-xl shadow-slate-900/10 sm:block">
                                <div class="flex items-center gap-3"><x-heroicon-o-clock class="h-6 w-6 text-indigo-600" /><div><p class="text-sm font-semibold text-slate-900">SLA Monitoring</p><p class="text-xs font-medium text-slate-500">95% On Track</p></div></div>
                            </div>
                            <div class="absolute -bottom-6 left-12 hidden rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-xl shadow-slate-900/10 sm:block">
                                <div class="flex items-center gap-3"><x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" /><div><p class="text-sm font-semibold text-slate-900">Assigned Tickets</p><p class="text-xs font-medium text-slate-500">4 Active</p></div></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="reveal-section border-y border-slate-200 bg-white/70 py-24">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-3xl text-center">
                            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">WHY NEXADESK</p>
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Mengapa Memilih NexaDesk?</h2>
                            <p class="mt-4 text-base leading-7 text-slate-600">Dirancang untuk membantu tim IT mengelola ticket secara efisien dengan workflow yang jelas dan terukur.</p>
                        </div>

                        <div class="features-grid mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 transition hover:shadow-lg hover:shadow-slate-900/10"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-envelope class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Mail-to-Ticket</h3><p class="mt-2 text-sm leading-6 text-slate-600">Email masuk otomatis diproses menjadi ticket.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 transition hover:shadow-lg hover:shadow-slate-900/10"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-clock class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">SLA Monitoring</h3><p class="mt-2 text-sm leading-6 text-slate-600">Pantau target penyelesaian ticket berdasarkan prioritas.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 transition hover:shadow-lg hover:shadow-slate-900/10"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Assigned Technician</h3><p class="mt-2 text-sm leading-6 text-slate-600">Setiap ticket memiliki penanggung jawab yang jelas.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 transition hover:shadow-lg hover:shadow-slate-900/10"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-list-bullet class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Activity Timeline</h3><p class="mt-2 text-sm leading-6 text-slate-600">Riwayat penanganan ticket tercatat lengkap.</p></article>
                        </div>
                    </div>
                </section>

                <section id="workflow" class="workflow-section reveal-section py-24">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-3xl text-center"><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">WORKFLOW</p><h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Bagaimana NexaDesk Bekerja?</h2></div>
                        <div class="mt-14 grid gap-6 lg:grid-cols-5">
                            <div class="workflow-step relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-chat-bubble-left-right class="h-6 w-6 text-indigo-600" /></div><p class="mt-5 text-sm font-semibold uppercase tracking-wide text-indigo-600">Step 1</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Laporkan Masalah</h3><x-heroicon-o-arrow-right class="absolute -right-5 top-1/2 hidden h-6 w-6 -translate-y-1/2 text-slate-300 lg:block" /></div>
                            <div class="workflow-step relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-ticket class="h-6 w-6 text-indigo-600" /></div><p class="mt-5 text-sm font-semibold uppercase tracking-wide text-indigo-600">Step 2</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Ticket Dibuat</h3><x-heroicon-o-arrow-right class="absolute -right-5 top-1/2 hidden h-6 w-6 -translate-y-1/2 text-slate-300 lg:block" /></div>
                            <div class="workflow-step relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-user class="h-6 w-6 text-indigo-600" /></div><p class="mt-5 text-sm font-semibold uppercase tracking-wide text-indigo-600">Step 3</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Ditugaskan ke Teknisi</h3><x-heroicon-o-arrow-right class="absolute -right-5 top-1/2 hidden h-6 w-6 -translate-y-1/2 text-slate-300 lg:block" /></div>
                            <div class="workflow-step relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-wrench-screwdriver class="h-6 w-6 text-indigo-600" /></div><p class="mt-5 text-sm font-semibold uppercase tracking-wide text-indigo-600">Step 4</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Proses Penanganan</h3><x-heroicon-o-arrow-right class="absolute -right-5 top-1/2 hidden h-6 w-6 -translate-y-1/2 text-slate-300 lg:block" /></div>
                            <div class="workflow-step rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-check-circle class="h-6 w-6 text-indigo-600" /></div><p class="mt-5 text-sm font-semibold uppercase tracking-wide text-indigo-600">Step 5</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Masalah Selesai</h3></div>
                        </div>
                    </div>
                </section>

                <section id="dashboard" class="reveal-section border-y border-slate-200 bg-white/70 py-24">
                    <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2 lg:px-8">
                        <div><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">DASHBOARD OVERVIEW</p><h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Pantau Semua Ticket dalam Satu Dashboard</h2><p class="mt-4 text-base leading-7 text-slate-600">Dashboard NexaDesk membantu admin dan technician melihat status ticket, prioritas, SLA, dan aktivitas terbaru secara cepat.</p><div class="mt-8 space-y-4 text-sm font-semibold text-slate-700"><div class="flex items-center gap-3"><x-heroicon-o-chart-bar class="h-6 w-6 text-indigo-600" />Statistik ticket real-time</div><div class="flex items-center gap-3"><x-heroicon-o-clock class="h-6 w-6 text-indigo-600" />Pemantauan SLA yang jelas</div><div class="flex items-center gap-3"><x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" />Monitoring beban kerja teknisi</div></div></div>
                        <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-900/10"><div class="flex items-center gap-2 border-b border-slate-200 px-4 py-3"><span class="h-3 w-3 rounded-full bg-rose-400"></span><span class="h-3 w-3 rounded-full bg-amber-400"></span><span class="h-3 w-3 rounded-full bg-emerald-400"></span></div><img src="{{ asset('docs/section-1.png') }}" alt="NexaDesk dashboard overview" class="aspect-[16/10] w-full rounded-2xl object-cover object-top"></div>
                    </div>
                </section>

                <section class="reveal-section py-24">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-3xl text-center"><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">CORE FEATURES</p><h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Fitur Utama untuk Workflow Helpdesk Modern</h2><p class="mt-4 text-base leading-7 text-slate-600">Dari laporan masuk hingga penyelesaian, NexaDesk menjaga seluruh proses tetap rapi, terukur, dan terdokumentasi.</p></div>
                        <div class="features-grid mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-ticket class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Ticket Management</h3><p class="mt-2 text-sm leading-6 text-slate-600">Kelola ticket berdasarkan status, prioritas, kategori, dan department.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-envelope class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Mail-to-Ticket</h3><p class="mt-2 text-sm leading-6 text-slate-600">Email masuk otomatis diproses menjadi ticket.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-clock class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">SLA Tracking</h3><p class="mt-2 text-sm leading-6 text-slate-600">Pantau batas waktu penyelesaian berdasarkan prioritas.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Technician Assignment</h3><p class="mt-2 text-sm leading-6 text-slate-600">Ticket dapat ditugaskan ke technician yang sesuai.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-document-text class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Internal Notes</h3><p class="mt-2 text-sm leading-6 text-slate-600">Staff dapat menambahkan catatan internal tanpa terlihat user.</p></article>
                            <article class="feature-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><x-heroicon-o-list-bullet class="h-6 w-6 text-indigo-600" /></div><h3 class="mt-5 text-lg font-semibold text-slate-900">Activity Timeline</h3><p class="mt-2 text-sm leading-6 text-slate-600">Setiap perubahan ticket tercatat sebagai riwayat aktivitas.</p></article>
                        </div>
                    </div>
                </section>

                <section class="stats-section reveal-section border-y border-slate-200 bg-white/70 py-24">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-3xl text-center"><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">SYSTEM IMPACT</p><h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Dibangun untuk Proses Support yang Lebih Terukur</h2></div>
                        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="stat-card rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-indigo-50 p-7 shadow-sm"><p class="text-3xl font-extrabold tracking-tight text-slate-950">500+</p><p class="mt-2 text-sm leading-6 text-slate-600">Ticket Demo</p></div>
                            <div class="stat-card rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-indigo-50 p-7 shadow-sm"><p class="text-3xl font-extrabold tracking-tight text-slate-950">95%</p><p class="mt-2 text-sm leading-6 text-slate-600">SLA Tracking</p></div>
                            <div class="stat-card rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-indigo-50 p-7 shadow-sm"><p class="text-3xl font-extrabold tracking-tight text-slate-950">4</p><p class="mt-2 text-sm leading-6 text-slate-600">Role Workflow</p></div>
                            <div class="stat-card rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-indigo-50 p-7 shadow-sm"><p class="text-3xl font-extrabold tracking-tight text-slate-950">24/7</p><p class="mt-2 text-sm leading-6 text-slate-600">Email Intake</p></div>
                        </div>
                    </div>
                </section>

                <section id="faq" class="reveal-section py-24">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-3xl text-center"><p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">FAQ</p><h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-4xl">Pertanyaan Umum</h2></div>
                        <div class="mt-14 grid gap-6 lg:grid-cols-2">
                            <article class="rounded-2xl border border-slate-200 bg-white p-6"><h3 class="text-lg font-semibold text-slate-900">Apakah user bisa membuat ticket lewat website?</h3><p class="mt-2 text-sm leading-6 text-slate-600">Ya, user dapat membuat ticket melalui form web dengan kategori, prioritas, dan attachment.</p></article>
                            <article class="rounded-2xl border border-slate-200 bg-white p-6"><h3 class="text-lg font-semibold text-slate-900">Apakah NexaDesk bisa menerima ticket dari email?</h3><p class="mt-2 text-sm leading-6 text-slate-600">Ya, NexaDesk sudah mendukung Mail-to-Ticket berbasis IMAP dan scheduler.</p></article>
                            <article class="rounded-2xl border border-slate-200 bg-white p-6"><h3 class="text-lg font-semibold text-slate-900">Apakah staff bisa membalas ticket lewat email?</h3><p class="mt-2 text-sm leading-6 text-slate-600">Ya, staff dapat mengirim balasan email langsung dari detail ticket menggunakan template balasan.</p></article>
                            <article class="rounded-2xl border border-slate-200 bg-white p-6"><h3 class="text-lg font-semibold text-slate-900">Apakah ada SLA?</h3><p class="mt-2 text-sm leading-6 text-slate-600">Ya, setiap ticket memiliki SLA berdasarkan prioritas dan ditampilkan melalui indikator status.</p></article>
                        </div>
                    </div>
                </section>

                <section class="reveal-section px-6 pb-24 lg:px-8">
                    <div class="mx-auto max-w-7xl rounded-3xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-14 text-center text-white shadow-2xl shadow-indigo-900/20 sm:px-12">
                        <h2 class="text-3xl font-bold tracking-tight lg:text-4xl">Siap Mengelola Laporan IT Lebih Cepat?</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-indigo-50">Gunakan NexaDesk untuk membuat, memantau, dan menyelesaikan ticket IT dalam satu workflow yang jelas.</p>
                        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                            @if (Route::has('tickets.create'))<a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-base font-semibold text-indigo-700 transition hover:bg-indigo-50">Create Ticket</a>@endif
                            @if (Route::has('login'))<a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/40 px-6 py-3 text-base font-semibold text-white transition hover:bg-white/10">Login Dashboard</a>@endif
                        </div>
                    </div>
                </section>
            </main>

            <footer class="bg-slate-950 text-slate-300">
                <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
                    <div class="grid gap-10 lg:grid-cols-[1.4fr_repeat(3,1fr)]">
                        <div><div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10"><x-heroicon-o-chat-bubble-left-right class="h-6 w-6 text-indigo-300" /></span><span class="text-lg font-bold text-white">NexaDesk</span></div><p class="mt-4 max-w-sm text-sm leading-6 text-slate-400">Modern Helpdesk Management System for IT support workflow.</p></div>
                        <div><h3 class="text-sm font-semibold text-white">Product</h3><ul class="mt-4 space-y-3 text-sm"><li><a href="#features" class="hover:text-white">Features</a></li><li><a href="#workflow" class="hover:text-white">Workflow</a></li><li><a href="#dashboard" class="hover:text-white">Dashboard</a></li><li><a href="#faq" class="hover:text-white">FAQ</a></li></ul></div>
                        <div><h3 class="text-sm font-semibold text-white">Support</h3><ul class="mt-4 space-y-3 text-sm"><li><a href="{{ Route::has('tickets.create') ? route('tickets.create') : '#home' }}" class="hover:text-white">Create Ticket</a></li><li><a href="#features" class="hover:text-white">Mail-to-Ticket</a></li><li><a href="#features" class="hover:text-white">SLA Tracking</a></li><li><a href="#workflow" class="hover:text-white">Technician Queue</a></li></ul></div>
                        <div><h3 class="text-sm font-semibold text-white">System</h3><ul class="mt-4 space-y-3 text-sm"><li>Laravel</li><li>MySQL</li><li>Docker Sail</li><li>Gmail IMAP</li></ul></div>
                    </div>
                    <div class="mt-12 border-t border-white/10 pt-8 text-sm text-slate-500">? 2026 NexaDesk. Built for Helpdesk IT UAS Project.</div>
                </div>
            </footer>
        </div>
    </body>
</html>
