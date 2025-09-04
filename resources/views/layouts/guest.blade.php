<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon -->
    <link rel="icon" href="{{ Storage::url(setting('favicon', 'favicon.ico')) }}" type="image/x-icon" />

    <title>{{ setting('site_name', config('app.name')) }}</title>

    <!-- Your CSS and JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @isset($style)
        {{ $style }}
    @endisset
    @stack('styles')
</head>

<body class="font-sans bg-light">

    <div class="d-flex min-vh-100 flex-column">

        @include('layouts.navigation')

        <div class="d-flex flex-grow-1">
            <main class="flex-grow-1">
                {{ $slot }}
            </main>
        </div>

        @include('layouts.footer')
    </div>
    @isset($script)
        {{ $script }}
    @endisset
    @stack('scripts')

</body>

</html>
