<x-layouts.app>
    <div class="h-full flex flex-col items-center mt-25">
        <p class="heading">Great to have you back, {{ Auth::user()->name }}</p>
        <p class="sub-heading mt-4">Let's Get Started With..</p>
        <diV class="flex gap-10 justify-center mt-8">
            <x-layouts.card url="{{ route('citizen.pothole.index') }}"
                class="flex flex-col gap-4 items-center w-50 cursor-pointer">
                <x-logo class="size-18 text-gray-700" />
                <p class="sub-heading text-center">Pothole List</p>
            </x-layouts.card>

            <x-layouts.card url="{{ route('citizen.pothole.create') }}"
                class="flex flex-col gap-4 items-center w-50 cursor-pointer">
                <div class="flex flex-1">
                    <x-icons.create class="size-13 self-center text-gray-600" />
                </div>
                <p class="sub-heading text-center">Report Pothole</p>
            </x-layouts.card>
        </diV>
    </div>
</x-layouts.app>
