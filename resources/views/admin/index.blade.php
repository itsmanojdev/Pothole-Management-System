<x-layouts.app>
    <div class="flex justify-between">
        <h1 class="heading">List of Admins</h1>
        <x-button type="primary" :href="route('citizen.pothole.create')">Create Admin</x-button>
    </div>

    <div class="flex flex-col gap-6 mt-6">
        <form id="admin-search-form" class="space-y-4">
            <x-form-field type="text" name="search" class="px-4 text-base" value="{{ request('search') }}"
                placeholder="Search By Name... " />
            <x-form-field type="dropdown" name="role" text="Role: " :options="['all', 'admin', 'super-admin']" />
        </form>
        @if (count($admins))
            @foreach ($admins as $admin)
                {{-- <x-index-card resource="pothole" :content="$pothole" /> --}}
                @include('components.index-card', [
                    'resource' => 'admin',
                    'showURL' => route('admin.show', $admin->id),
                    'id' => $admin->id,
                    'title' => $admin->name,
                    'content' => "Role: {$admin->role->value}",
                    'image_path' => $admin->profile_pic ?? Vite::asset('resources/images/profile_default.jpg'),
                ])
            @endforeach
        @else
            <p class="text-gray-600 text-medium text-lg tracking-wide">Oops!! No Admin Records Found</p>
        @endif

        {{ $admins->links() }}
    </div>
    @pushOnce('scripts')
        <script>
            window.addEventListener('load', () => {
                let adminSearchForm = document.querySelector("#admin-search-form");
                let roleDropdown = document.querySelector("#role-dropdown-wrapper");

                // Submit when role dropdown changes
                roleDropdown.addEventListener('change', () => adminSearchForm.submit());
            })
        </script>
    @endPushOnce
</x-layouts.app>
