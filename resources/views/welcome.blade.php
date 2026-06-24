```blade
<section class="relative overflow-hidden bg-white">
    
    <!-- Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 via-white to-purple-100"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-24">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- Left -->
            <div>

                <div
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white px-4 py-2 shadow-sm">

                    <span class="text-xs font-semibold text-indigo-600">
                        NEW
                    </span>

                    <span class="text-sm text-slate-700">
                        University Helpdesk System
                    </span>

                </div>

                <h1
                    class="mt-8 text-5xl lg:text-7xl font-bold leading-tight">

                    <span
                        class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">

                        Smart Ticketing
                    </span>

                    <br>

                    for Modern
                    IT Support

                </h1>

                <p
                    class="mt-8 text-xl text-slate-600 leading-relaxed">

                    NexaDesk centralizes ticket management,
                    technician assignment, SLA monitoring,
                    and support communication into a single
                    easy-to-use platform.

                </p>

                <div class="mt-10 flex gap-4">

                    <a href="{{ route('register') }}"
                        class="px-8 py-4 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold transition">

                        Get Started

                    </a>

                    <a href="#features"
                        class="px-8 py-4 rounded-xl border border-slate-300 hover:bg-slate-50 font-semibold">

                        Explore Features

                    </a>

                </div>

                <div class="mt-12 flex gap-10">

                    <div>
                        <h3 class="text-3xl font-bold">100+</h3>
                        <p class="text-slate-500">
                            Demo Users
                        </p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold">24/7</h3>
                        <p class="text-slate-500">
                            Ticket Monitoring
                        </p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold">99%</h3>
                        <p class="text-slate-500">
                            SLA Visibility
                        </p>
                    </div>

                </div>

            </div>

            <!-- Right -->
            <div class="relative">

                <!-- Main Dashboard Card -->
                <div
                    class="rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden">

                    <div
                        class="h-16 bg-gradient-to-r from-cyan-200 to-indigo-200 flex items-center px-6">

                        <div class="flex gap-2">
                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        </div>

                    </div>

                    <div class="p-8">

                        <h2 class="text-4xl font-bold mb-8">
                            Welcome, Admin 👋
                        </h2>

                        <div class="grid grid-cols-3 gap-4 mb-8">

                            <div class="bg-blue-50 p-4 rounded-xl">
                                <p class="text-sm text-slate-500">
                                    Open Tickets
                                </p>
                                <h3 class="text-3xl font-bold text-blue-600">
                                    45
                                </h3>
                            </div>

                            <div class="bg-green-50 p-4 rounded-xl">
                                <p class="text-sm text-slate-500">
                                    Resolved
                                </p>
                                <h3 class="text-3xl font-bold text-green-600">
                                    84
                                </h3>
                            </div>

                            <div class="bg-red-50 p-4 rounded-xl">
                                <p class="text-sm text-slate-500">
                                    SLA Risk
                                </p>
                                <h3 class="text-3xl font-bold text-red-600">
                                    12
                                </h3>
                            </div>

                        </div>

                        <div class="space-y-4">

                            <div class="border rounded-xl p-4">
                                <div class="flex justify-between">
                                    <span>Email Access Issue</span>
                                    <span
                                        class="text-red-500 font-semibold">
                                        High
                                    </span>
                                </div>
                            </div>

                            <div class="border rounded-xl p-4">
                                <div class="flex justify-between">
                                    <span>Network Connectivity</span>
                                    <span
                                        class="text-yellow-500 font-semibold">
                                        Medium
                                    </span>
                                </div>
                            </div>

                            <div class="border rounded-xl p-4">
                                <div class="flex justify-between">
                                    <span>Printer Offline</span>
                                    <span
                                        class="text-green-500 font-semibold">
                                        Low
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Floating Card -->
                <div
                    class="absolute -top-5 -right-5 bg-violet-600 text-white px-5 py-3 rounded-2xl shadow-xl">

                    ⚡ SLA Monitoring Active

                </div>

            </div>

        </div>

    </div>

</section>
```
