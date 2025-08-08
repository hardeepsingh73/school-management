<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Sidebar toggle button -->
            <button id="sidebarToggle" class="navbar-toggler d-lg-none me-2" type="button" aria-label="Toggle sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand/logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="Home">
                <i class="bi bi-rocket-takeoff me-2"></i>
                <span class="d-none d-sm-inline fw-bold">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <!-- Main navbar toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarContent"
                aria-controls="mainNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="mainNavbarContent">
                <!-- Left side navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door d-lg-none me-2"></i>Home
                        </a>
                    </li>
                    @can('view dashboard')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-1 d-none d-lg-inline"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endcan
                </ul>

                <!-- Right side navigation -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right d-lg-none me-2"></i>
                                    <span>{{ __('Login') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                                    href="{{ route('register') }}">
                                    <i class="bi bi-person-plus d-lg-none me-2"></i>
                                    <span>{{ __('Register') }}</span>
                                </a>
                            </li>
                        @endif
                    @else
                        <!-- User dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-lg-inline me-2">{{ Auth::user()->name }}</span>
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>
                                        {{ __('Profile') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="bi bi-gear me-2"></i>
                                        {{ __('Settings') }}
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

@auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@endauth
