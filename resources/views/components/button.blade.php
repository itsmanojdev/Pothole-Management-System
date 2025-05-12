@props(['type' => 'primary'])

@php
    $commonClass = 'font-medium shadow-md px-4 py-2 text-sm tracking-wide rounded-sm cursor-pointer border-1 animate';
@endphp

@switch($type)
    @case('primary')
        <a
            {{ $attributes->merge(['class' => "bg-primary-normal text-gray-100 hover:border-primary-xdark hover:bg-primary-dark active:bg-primary-xdark $commonClass"]) }}>
            {{ $slot }}
        </a>
    @break

    @case('outline')
        <a
            {{ $attributes->merge(['class' => "bg-indigo-50 text-gray-800 border-gray-600 hover:border-gray-800 hover:bg-indigo-100 active:bg-indigo-200/70 $commonClass"]) }}>
            {{ $slot }}
        </a>
    @break

    @default
@endswitch
