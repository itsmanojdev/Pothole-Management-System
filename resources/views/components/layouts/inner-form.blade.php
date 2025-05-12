@props(['theme' => 'yellow'])

@php
    $themeYellow = 'bg-yellow-100/50 hover:bg-yellow-100/70';
    $themeBlue = 'bg-indigo-100/50 hover:bg-indigo-100/70';
    $themeRed = 'bg-red-100/50 hover:bg-red-100/70';

    $currTheme = match ($theme) {
        'yellow' => $themeYellow,
        'blue' => $themeBlue,
        'red' => $themeRed,
    };
@endphp

<div {{ $attributes->merge(['class' => "p-6 mb-6 border-2 border-gray-400 rounded-md shadow-md $currTheme"]) }}>
    @isset($title)
        <h2 class="text-xl font-medium tracking-wide">{{ $title }}</h2>
    @endisset
    {{ $slot }}
</div>
