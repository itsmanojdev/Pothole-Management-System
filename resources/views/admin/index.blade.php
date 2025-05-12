<x-layouts.app>
    <div class="flex justify-between">
        <h1 class="heading">List of Admins</h1>
        <x-button type="primary" :href="route('citizen.pothole.create')">Create Admin</x-button>
    </div>

    <div class="flex flex-col gap-6 mt-6">
        <form id="pothole-search-form" class="space-y-4">
            <x-form-field type="text" name="search" class="px-4 text-base" value="{{ request('search') }}"
                placeholder="Search By Name... " />
            <x-form-field type="dropdown" name="role" text="Role: " :options="['Admin', 'Super Admin']" />
        </form>
        @if (count($admins))
            @foreach ($admins as $admin)
                {{-- <x-index-card resource="pothole" :content="$pothole" /> --}}
                @include('components.index-card', [
                    'resource' => 'admin',
                    'id' => $admin->id,
                    'title' => $admin->name,
                    'footer' => "Role: {$admin->role->value}",
                    // 'image_path' => $pothole->image_path ?? Vite::asset('resources/images/pothole_default.png'),
                ])
            @endforeach
        @else
            <p class="text-gray-600 text-medium text-lg tracking-wide">Oops!! No Pothole Records Available</p>
        @endif

        {{ $admins->links() }}
    </div>
    @pushOnce('scripts')
        <script>
            window.addEventListener('load', () => {
                let potholeSearchForm = document.querySelector("#pothole-search-form");
                let adminFiltersInput = document.querySelector("#admin-filter-wrapper");
                let statusDropdown = document.querySelector("#status-dropdown-wrapper");

                @if (Auth::user()->isAdmin())
                    // To display status dropdown based on admin filter
                    const params = new URLSearchParams(window.location.search);
                    if (params.get('admin-filter') == CONSTANTS.ADMIN_FILTERS.OPEN_POTHOLES) {
                        statusDropdown.style.display = 'none';
                    }

                    // Submit when admin filter changes
                    adminFiltersInput.addEventListener('change', () => {
                        if (event.target.value == CONSTANTS.ADMIN_FILTERS.OPEN_POTHOLES) {
                            document.querySelector("#status").value = '';
                        }
                        potholeSearchForm.submit();
                    });
                @endif

                // Submit when status dropdown changes
                statusDropdown.addEventListener('change', () => potholeSearchForm.submit());
            })
        </script>
    @endPushOnce
</x-layouts.app>
