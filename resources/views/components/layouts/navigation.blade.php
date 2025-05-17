<nav class="bg-white shadow-md text-sm">
    <div class="mx-auto h-[56px] flex justify-between md:px-16 px-8">
        <div class="flex gap-4">
            <div class="flex">
                <a href="{{ route('home') }}" class="self-center">
                    <x-logo class="size-9 text-gray-900" />
                </a>
            </div>
            <div class="flex">
                @auth
                    {{-- @if (Auth::user()->isCitizen())
                        <x-nav-link :href="route('citizen.dashboard')" :active="request()->routeIs('citizen.dashboard')">Dashboard</x-nav-link>
                        <x-nav-link :href="route('citizen.pothole.index')" :active="request()->routeIs('pothole.index')">Potholes</x-nav-link>
                    @else
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-nav-link>
                        <x-nav-link :href="route('admin.pothole.index')" :active="request()->routeIs('pothole.index')">Pothole Managment</x-nav-link>
                        @if (Auth::user()->isSuperAdmin())
                            <x-nav-link :href="route('admin.management.index')" :active="request()->routeIs('admin.management.index')">Admin Management</x-nav-link>
                        @endif
                    @endif --}}
                    <x-nav-link :href="userRoute('dashboard')" :active="userRouteIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="userRoute('pothole.index')" :active="userRouteIs('pothole.*')">Pothole Management</x-nav-link>
                    @if (Auth::user()->isSuperAdmin())
                        <x-nav-link :href="userRoute('management.index')" :active="userRouteIs('management.*')">Admin Management</x-nav-link>
                    @endif
                @endauth

                @guest
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">About</x-nav-link>
                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">Contact</x-nav-link>
                @endguest
            </div>
        </div>
        <div class="flex">
            @guest
                <div class="flex gap-3 items-center">
                    <x-button type="outline" :href="route('login')">Login</x-button>
                    <x-button type="primary" :href="route('register')">Register</x-button>
                </div>
            @endguest

            @auth
                <x-nav-link :href="route('user.profile.show', Auth::id())" :active="request()->routeIs('user.profile.*')">Profile</x-nav-link>

                <form method="POST" action="{{ route('logout') }}" class="ml-2 inline-flex items-center">
                    @csrf
                    <x-form-field type='submit' value="Log out" class="text-sm tracking-wide" />
                </form>
            @endauth
        </div>
    </div>
</nav>
