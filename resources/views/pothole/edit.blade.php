<x-layouts.app>
    <x-slot name="title">
        Edit Pothole
    </x-slot>

    <x-forms.pothole mode="edit" :pothole="$pothole" />

    @pushOnce('scripts')
        <script>
            window.addEventListener("load", async () => {
                //Map Initialization
                try {
                    await initMap("edit");
                } catch (error) {
                    console.log("Google Maps error", error);
                    toast("error", "Failed to load map.");
                }

                //Form Update Request
                // updateFormRequest("pothole-edit", "/citizen/potholes/{{ $pothole->id }}")
            });
        </script>
    @endPushOnce
</x-layouts.app>
