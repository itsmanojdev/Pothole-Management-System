<div onclick="window.location.href='{{ isset($showURL) ? $showURL : userRoute($resource . '.show', $id) }}'"
    class="flex p-4 bg-indigo-100 cursor-pointer border border-gray-300 rounded-md shadow-md gap-4 hover:bg-indigo-200/60 animate">
    <div class="size-24">
        <img src="{{ getImageSource($image_path) }}" alt="{{ $title }}" alt="{{ $title }}"
            class="image-cover rounded-lg">
    </div>
    <div class="flex-1 flex flex-col justify-center">
        @isset($tag)
            <p class="text-muted text-xs">
                {{ $tag }}</p>
        @endisset
        <h3 class="text-lg text-heading font-bold">{{ $title }}</h3>
        @isset($content)
            <p class="text-sm mt-0.5">{{ $content }}</p>
        @endisset
        @isset($footer)
            <p class="text-muted text-xs mt-auto">{{ $footer }}</p>
        @endisset
    </div>
    @if ($resource == 'pothole')
        <div class="flex mr-4">
            <x-button href="https://maps.google.com/?q={{ $pothole->latitude }},{{ $pothole->longitude }}"
                target="_blank" class="inline-block self-center py-4" onclick="event.stopPropagation();">
                <x-icons.location class="size-8 text-white" />
            </x-button>
        </div>
    @endif
</div>
