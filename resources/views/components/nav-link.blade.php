@props(['active' => false])

@php
    $classes = 'inline-flex items-center mx-2 px-2 border-b-3 hover:bg-gray-50 animate';
    $classes .= $active ? ' border-primary-normal' : ' border-gray-300 hover:border-gray-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
