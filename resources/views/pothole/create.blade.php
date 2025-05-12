<x-layouts.app>
    <x-slot name="title">
        Create Pothole
    </x-slot>

    <x-forms.pothole mode="create" />

    @pushOnce('scripts')
        <script>
            window.addEventListener("load", async () => {
                //Map Initialization
                try {
                    await initMap("create");
                } catch (error) {
                    console.log("Google Maps error", error);
                    toast("error", "Failed to load map.");
                }
            });
        </script>
    @endPushOnce
</x-layouts.app>
