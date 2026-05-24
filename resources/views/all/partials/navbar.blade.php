<style>
    .navbar {
        z-index: 9999;
    }

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

    .custom-dropdown {
        position: relative;
    }

    .custom-menu {
        position: absolute;
        top: 110%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        min-width: 160px;
        display: none;
        z-index: 9999;
    }

    .custom-menu.show {
        display: block;
    }

    .custom-menu button {
        width: 100%;
        border: none;
        background: none;
        padding: 10px 12px;
        text-align: left;
    }

    .custom-menu button:hover {
        background: #f1f1f1;
    }
</style>

@php
    $isDashboard = request()->is('dashboard*') 
        || request()->is('admin*');
@endphp

<nav class="navbar navbar-expand-lg fixed-top navbar-blur shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="/">
            <i class="fas fa-map-marked-alt text-primary me-1"></i>
            Visit<span class="text-primary">MERAUKE</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2 fw-medium">

                {{-- BERANDA --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('/') ? 'active' : '' }}" href="/">
                        Beranda
                    </a>
                </li>

                {{-- WISATA --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('wisata*') ? 'active' : '' }}"
                        href="{{ route('wisata') }}">
                        Wisata
                    </a>
                </li>

                {{-- PRODUK --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('produk*') ? 'active' : '' }}"
                        href="{{ route('produk.index') }}">
                        Produk
                    </a>
                </li>

                {{-- ARTIKEL --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('artikel*') ? 'active' : '' }}"
                        href="{{ route('artikel.index') }}">
                        Artikel
                    </a>
                </li>

                {{-- TENTANG --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('tentang.kami') ? 'active' : '' }}"
                        href="{{ route('tentang.kami') }}">
                        Tentang Kami
                    </a>
                </li>

                {{-- KONTAK --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->routeIs('kontak.kami') ? 'active' : '' }}"
                        href="{{ route('kontak.kami') }}">
                        Kontak
                    </a>
                </li>

                {{-- QUIZ --}}
                <li class="nav-item">
                    <a class="nav-link nav-pill {{ request()->is('quiz*') ? 'active' : '' }}"
                        href="{{ route('quiz.index') }}">
                        Quiz
                    </a>
                </li>

                {{-- GUEST --}}
                @guest
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm nav-pill px-3">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                @endguest

                {{-- AUTH --}}
                @auth
                    @php
                        $role = auth()->user()->role;
                        $dashboardRoute = $role === 'admin'
                            ? route('admin.dashboard')
                            : route('dashboard');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link nav-pill {{ $isDashboard ? 'active' : '' }}"
                            href="{{ $dashboardRoute }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item custom-dropdown ms-2">
                        <button onclick="toggleDropdown()" class="btn btn-primary btn-sm nav-pill px-3">
                            <i class="fas fa-user me-1"></i>
                            {{ auth()->user()->name }}
                        </button>

                        <div id="dropdownMenu" class="custom-menu">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-danger">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('show');
}

document.addEventListener('click', function(event) {
    let dropdown = document.querySelector('.custom-dropdown');
    if (!dropdown.contains(event.target)) {
        document.getElementById('dropdownMenu').classList.remove('show');
    }
});
</script>