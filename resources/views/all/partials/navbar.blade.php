<style>
    .nav-pill {
        padding: 4px 8px;
        border-radius: 5px;
        transition: all 0.3s ease;
        color: #555;
    }

    .nav-pill:hover {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .nav-pill.active {
        background-color: #0d6efd;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top navbar-blur shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <i class="fas fa-map-marked-alt text-primary me-1"></i>
            JELAJAHI<span class="text-primary">.MY.ID</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2 fw-medium">

                <!-- Public Menu -->
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('/') ? 'active' : '' }}" href="/">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('wisata') ? 'active' : '' }}"
                        href="{{ route('wisata') }}">
                        Wisata
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('tentang.kami') ? 'active' : '' }}"
                        href="{{ route('tentang.kami') }}">
                        Tentang Kami
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('kontak.kami') ? 'active' : '' }}"
                        href="{{ route('kontak.kami') }}">
                        Kontak
                    </a>
                </li>

                <!-- Quiz -->
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('quiz*') ? 'active' : '' }}"
                        href="{{ route('quiz.index') }}">
                        Quiz
                    </a>
                </li>

                <!-- Guest -->
                @guest
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm nav-pill px-3">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                @endguest

                <!-- Auth -->
                @auth
                    @php
                        $role = auth()->user()->role;
                        $dashboardRoute = $role === 'admin'
                            ? route('admin.dashboard')
                            : route('dashboard');
                    @endphp

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link nav-pill {{ request()->routeIs('dashboard*') || request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ $dashboardRoute }}">
                            Dashboard
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown ms-2">
                        <a class="btn btn-primary btn-sm nav-pill px-3 dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>