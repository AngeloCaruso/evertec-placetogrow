@props(['active'])

@php

$classes = ($active ?? false)
            ? 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 bg-gray-50 text-gray-600'
            : 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-white hover:text-gray-600 hover:bg-gray-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
