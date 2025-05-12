<x-layouts.app>
    <div class="flex items-center w-3/5">
        <div>
            <h1 class="heading">Pothole Details</h1>
            <p class="text-muted text-sm">Status: {{ $pothole->status }}</p>
        </div>
        @if (Auth::user()->isCitizen())
            <x-button :href="route('citizen.pothole.edit', $pothole->id)" class="ml-24 px-8 text-base">Edit Pothole</x-button>
        @endif
    </div>

    <x-forms.pothole mode="show" :pothole="$pothole" />

    @pushOnce('scripts')
        <script>
            window.addEventListener("load", async () => {
                //Map Initialization
                try {
                    await initMap("show");
                } catch (error) {
                    console.log("Google Maps error", error);
                    toast("error", "Failed to load map.");
                }
            });
        </script>
    @endPushOnce
</x-layouts.app>
