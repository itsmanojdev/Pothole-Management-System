<x-layouts.app>
    <div class="flex gap-4">
        <x-button :href="route('db.user')">Users</x-button>
        <x-button :href="route('db.pothole')">Potholes</x-button>
    </div>
    <div>
        <table class="w-full my-4">
            <thead class="border border-t-gray-600 bg-indigo-200">
                <tr>
                    <th>Id</th>
                    <th>Profile pic</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile no</th>
                    <th>Aadhaar no</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="size-24">
                                <img src="{{ getImageSource($user->profile_pic) }}" class="image-cover rounded-lg">
                            </div>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile_number }}</td>
                        <td>{{ $user->aadhaar_number }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
                @empty
                    <p>No Users Found</p>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</x-layouts.app>
