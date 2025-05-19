<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', 'Pothole Management System') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/pothole.png') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..600;1,300..600&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Maps API -->
    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })({
            key: "{{ config('services.google_maps.key') }}",
            v: "weekly",
        });
    </script>

    <!-- Additional Scripts If Any -->
    @stack('scripts')
</head>

<body class="h-dvh flex flex-col bg-blue-50 font-rubik text-text">
    <audio id="toast-success" src="{{ Vite::asset('resources/audio/success.mp3') }}" preload="auto"></audio>
    <audio id="toast-error" src="{{ Vite::asset('resources/audio/error.mp3') }}" preload="auto"></audio>
    <x-message-handler />

    <header class="sticky top-0 z-100">
        <x-layouts.navigation />
    </header>

    <main class="sm:px-16 p-8 flex-1" @class(['px-16', 'py-8', 'flex-1'])>
        @isset($title)
            <h1 class="heading">{{ $title }}</h1>
        @endisset

        {{ $slot }}
    </main>
</body>

</html>
