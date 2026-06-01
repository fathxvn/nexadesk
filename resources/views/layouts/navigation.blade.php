<nav>
    @php
        $user = auth()->user();
        $isStaff = $user && in_array($user->role, ['admin', 'technician']);
    @endphp

    <!-- Desktop Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-40 hidden border-r border-slate-200 bg-slate-50/80 backdrop-blur transition-all duration-300 ease-in-out lg:flex lg:flex-col"
        :class="sidebarCollapsed ? 'w-20' : 'w-64'"
    >
        <div
            class="relative flex h-16 items-center border-b border-slate-200 transition-all duration-300 ease-in-out"
            :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
        >
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3" title="NexaDesk">
                <x-application-logo class="block h-9 w-auto shrink-0 fill-current text-slate-700" />

                <div class="min-w-0 transition-all duration-300 ease-in-out" :class="sidebarCollapsed ? 'lg:sr-only' : ''">
                    <div class="truncate text-sm font-bold text-slate-700">NexaDesk</div>
                    <div class="truncate text-xs font-medium text-slate-500">Helpdesk Console</div>
                </div>
            </a>

            <button
                type="button"
                @click="sidebarCollapsed = ! sidebarCollapsed"
                class="absolute -right-4 top-1/2 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100 lg:inline-flex"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            >
                <span class="sr-only" x-text="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"></span>
                <x-heroicon-o-chevron-left x-show="! sidebarCollapsed" class="h-4 w-4" />
                <x-heroicon-o-chevron-right x-show="sidebarCollapsed" class="h-4 w-4" />
            </button>
        </div>

        <div class="flex flex-1 flex-col justify-between overflow-y-auto px-3 py-5">
            <div class="space-y-6">
                <div>
                    <div
                        class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 transition-all duration-300 ease-in-out"
                        :class="sidebarCollapsed ? 'lg:sr-only' : ''"
                    >
                        Workspace
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-nav-link icon="heroicon-o-home" :href="route('dashboard')" :active="request()->routeIs('dashboard')" title="Dashboard">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link icon="heroicon-o-ticket" :href="route('tickets.index')" :active="request()->routeIs('tickets.index', 'tickets.edit') || (! $isStaff && request()->routeIs('tickets.show'))" title="My Tickets">
                            {{ __('My Tickets') }}
                        </x-nav-link>

                        <x-nav-link icon="heroicon-o-plus-circle" :href="route('tickets.create')" :active="request()->routeIs('tickets.create')" title="Create Ticket">
                            {{ __('Create Ticket') }}
                        </x-nav-link>

                        @if ($isStaff)
                            <x-nav-link icon="heroicon-o-users" :href="route('staff.tickets.index')" :active="request()->routeIs('staff.tickets.*') || request()->routeIs('tickets.show')" title="Staff Tickets">
                                {{ __('Staff Tickets') }}
                            </x-nav-link>
                        @endif
                    </div>
                </div>

                <div>
                    <div
                        class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 transition-all duration-300 ease-in-out"
                        :class="sidebarCollapsed ? 'lg:sr-only' : ''"
                    >
                        Account
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-nav-link icon="heroicon-o-user-circle" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" title="Profile">
                            {{ __('Profile') }}
                        </x-nav-link>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <div
                    class="mb-3 px-3 transition-all duration-300 ease-in-out"
                    :class="sidebarCollapsed ? 'lg:sr-only' : ''"
                >
                    <div class="truncate text-sm font-semibold text-slate-700">{{ $user->name }}</div>
                    <div class="truncate text-xs text-slate-500">{{ $user->email }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        title="Log Out"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition-all duration-300 ease-in-out hover:bg-slate-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
                    >
                        <x-heroicon-o-arrow-left-on-rectangle class="h-5 w-5 shrink-0" />
                        <span class="truncate transition-all duration-300 ease-in-out" :class="sidebarCollapsed ? 'lg:sr-only' : ''">
                            {{ __('Log Out') }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur lg:hidden">
        <div class="flex h-16 items-center justify-between px-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-application-logo class="block h-8 w-auto fill-current text-slate-900" />
                <span class="text-sm font-bold text-slate-950">NexaDesk</span>
            </a>

            <button
                type="button"
                @click="mobileMenuOpen = true"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-300"
                aria-label="Open navigation menu"
            >
                <x-heroicon-o-bars-3 class="h-5 w-5" />
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div
        x-cloak
        x-show="mobileMenuOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 lg:hidden"
        aria-modal="true"
    >
        <button
            type="button"
            class="absolute inset-0 h-full w-full bg-slate-950/40"
            @click="mobileMenuOpen = false"
            aria-label="Close navigation menu"
        ></button>

        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex h-full w-full max-w-sm flex-col bg-white shadow-xl"
        >
            <div class="flex h-16 items-center justify-between border-b border-slate-200 px-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="block h-8 w-auto fill-current text-slate-900" />
                    <span class="text-sm font-bold text-slate-950">NexaDesk</span>
                </a>

                <button
                    type="button"
                    @click="mobileMenuOpen = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-950 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    aria-label="Close navigation menu"
                >
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-5">
                <div class="space-y-6">
                    <div>
                        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Workspace
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link icon="heroicon-o-home" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-responsive-nav-link>

                            <x-responsive-nav-link icon="heroicon-o-ticket" :href="route('tickets.index')" :active="request()->routeIs('tickets.index', 'tickets.edit') || (! $isStaff && request()->routeIs('tickets.show'))">
                                {{ __('My Tickets') }}
                            </x-responsive-nav-link>

                            <x-responsive-nav-link icon="heroicon-o-plus-circle" :href="route('tickets.create')" :active="request()->routeIs('tickets.create')">
                                {{ __('Create Ticket') }}
                            </x-responsive-nav-link>

                            @if ($isStaff)
                                <x-responsive-nav-link icon="heroicon-o-users" :href="route('staff.tickets.index')" :active="request()->routeIs('staff.tickets.*') || request()->routeIs('tickets.show')">
                                    {{ __('Staff Tickets') }}
                                </x-responsive-nav-link>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Account
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link icon="heroicon-o-user-circle" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-4">
                    <div class="mb-3 px-3">
                        <div class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</div>
                        <div class="truncate text-xs text-slate-500">{{ $user->email }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:bg-slate-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <x-heroicon-o-arrow-left-on-rectangle class="h-5 w-5 shrink-0" />
                            <span>{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
