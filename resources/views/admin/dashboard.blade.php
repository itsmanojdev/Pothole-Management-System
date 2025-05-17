<x-layouts.app>
    <div class="h-full flex flex-col items-center mt-25">
        <p class="heading">Great to have you back, {{ Auth::user()->name }}</p>
        <p class="sub-heading mt-4">Let's Get Started With..</p>
        <diV class="flex gap-10 justify-center mt-8">
            <x-layouts.card url="{{ route('admin.pothole.index') }}"
                class="flex flex-col items-center w-50 cursor-pointer">
                <x-logo class="size-25 text-gray-700" />
                <p class="sub-heading text-center">Pothole List</p>
            </x-layouts.card>

            @if (Auth::user()->isSuperAdmin())
                <x-layouts.card url="{{ route('admin.management.index') }}"
                    class="flex flex-col items-center w-50 cursor-pointer">
                    <div class="flex flex-1">
                        <x-icons.profile-default class="size-20 self-center text-transparent" />
                    </div>
                    <p class="sub-heading text-center">Admin List</p>
                </x-layouts.card>
            @endif
        </diV>
    </div>
</x-layouts.app>
