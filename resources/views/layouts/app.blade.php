<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="{{ config('app.description', 'Laravel Application') }}" />

    <title>{{ isset($title) ? "$title | " : '' }}{{ setting('site_name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Bootstrap & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-light min-vh-100 d-flex flex-column">

    <div class="d-flex flex-column flex-grow-1">

        {{-- Top Navigation --}}
        @include('layouts.navigation')

        <div class="d-flex flex-grow-1">

            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Main Content --}}
            <main id="main-content" class="flex-grow-1 p-4 bg-white shadow-sm rounded-3 overflow-auto" tabindex="-1">

                {{-- Page Header --}}
                @isset($header)
                    <header class="border-bottom pb-3 mb-4">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h1 class="h4 mb-2 mb-md-0 fw-bold text-dark">{{ $header }}</h1>
                            @isset($breadcrumbs)
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb small mb-0">
                                        {{ $breadcrumbs }}
                                    </ol>
                                </nav>
                            @endisset
                        </div>
                    </header>
                @endisset

                {{-- Flash Messages --}}
                <div class="container-fluid px-0">

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center">
                        <div class="col-xxl-10">
                            {{-- Page Slot Content --}}
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>
        </div>

        {{-- Footer --}}
        @include('layouts.footer')
    </div>
</body>

</html>
