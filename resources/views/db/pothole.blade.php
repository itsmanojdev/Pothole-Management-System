<x-layouts.app>
    <div class="flex gap-4">
        <x-button :href="route('db.user')">Users</x-button>
        <x-button :href="route('db.pothole')">Potholes</x-button>
    </div>
    <div>
        <table class="w-[150%] my-4">
            <thead class="border border-t-gray-600 bg-indigo-200">
                <tr>
                    <th>Id</th>
                    <th>pothole pic</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created by</th>
                    <th>Assigned to</th>
                    <th>Status</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>address</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($potholes as $pothole)
                    <tr>
                        <td>{{ $pothole->id }}</td>
                        <td>
                            <div class="size-24">
                                <img src="{{ getImageSource($pothole->image_path) }}" class="image-cover rounded-lg">
                            </div>
                        </td>
                        <td>{{ $pothole->title }}</td>
                        <td>{{ $pothole->description }}</td>
                        <td>{{ $pothole->creator->id . ' | ' . $pothole->creator->name }}</td>
                        <td>{{ $pothole->assignee?->id . ' | ' . $pothole->assignee?->name }}</td>
                        <td>{{ $pothole->status }}</td>
                        <td>{{ $pothole->latitude }}</td>
                        <td>{{ $pothole->longitude }}</td>
                        <td>{{ $pothole->address }}</td>
                        <td>{{ $pothole->created_at }}</td>
                        <td>{{ $pothole->updated_at }}</td>
                    </tr>
                @empty
                    <p>No Potholes Found</p>
                @endforelse
            </tbody>
        </table>

        {{ $potholes->links() }}
    </div>
</x-layouts.app>
