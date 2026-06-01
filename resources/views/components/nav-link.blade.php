@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
            ? 'flex w-full items-center gap-3 rounded-xl bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700 transition-all duration-300 ease-in-out'
            : 'flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition-all duration-300 ease-in-out hover:bg-slate-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} x-bind:class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''">
    @if ($icon)
        <x-dynamic-component :component="$icon" class="h-5 w-5 shrink-0" />
    @endif

    <span class="truncate transition-all duration-300 ease-in-out" :class="sidebarCollapsed ? 'lg:sr-only' : ''">
        {{ $slot }}
    </span>
</a>
