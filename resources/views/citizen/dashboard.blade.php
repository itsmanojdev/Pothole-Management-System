<x-layouts.app>
    <x-slot name="title">
        Citizen Dashboard Page
    </x-slot>
    {{ Auth::User()->role }}
    This is Dashboard Page
</x-layouts.app>
