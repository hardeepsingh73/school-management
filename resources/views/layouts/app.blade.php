<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{ config('app.description', 'Laravel Application') }}" />

    <title>{{ isset($title) ? "$title | " : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Your CSS and JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-light min-vh-100 d-flex flex-column">  

    <div class="d-flex flex-column min-vh-100">
        @include('layouts.navigation')

        <div class="d-flex flex-grow-1">
            @include('layouts.sidebar')

            <!-- Main content area -->
            <main id="main-content" class="flex-grow-1 p-3 p-md-4 overflow-auto" tabindex="-1">
                @isset($header)
                    <header class="mb-4">
                        <div class="container-fluid">
                            <h1 class="h3 mb-0">{{ $header }}</h1>
                            @isset($breadcrumbs)
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 mt-2">
                                        {{ $breadcrumbs }}
                                    </ol>
                                </nav>
                            @endisset
                        </div>
                    </header>
                @endisset

                <!-- Page content container with consistent padding -->
                <div class="container-fluid">
                    <!-- Flash messages -->
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>

        @include('layouts.footer')
    </div>
</body>

</html>