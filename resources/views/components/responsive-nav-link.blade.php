@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 rounded-xl bg-indigo-50 px-3 py-2.5 text-base font-semibold text-indigo-700 transition duration-150 ease-in-out'
            : 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:bg-slate-100 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" class="h-5 w-5 shrink-0" />
    @endif

    <span>{{ $slot }}</span>
</a>
