<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background: #0f172a;
    color: #fff;
    overflow-y: auto;
}

/* BRAND */
.sidebar .brand {
    font-size: 18px;
    font-weight: 700;
}

/* MENU */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #cbd5e1;
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 4px;
    transition: all 0.2s ease;
    font-size: 14px;
}

.sidebar a i {
    width: 18px;
    text-align: center;
}

/* HOVER */
.sidebar a:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
}

/* ACTIVE */
.sidebar a.active {
    background: #2563eb;
    color: #fff;
    font-weight: 600;
}

/* SECTION TITLE */
.sidebar .menu-title {
    font-size: 12px;
    color: #94a3b8;
    text-transform: uppercase;
    margin: 20px 0 8px;
    padding: 0 6px;
}

/* SCROLL */
.sidebar::-webkit-scrollbar {
    width: 5px;
}
.sidebar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 10px;
}
</style>


<div class="sidebar position-fixed p-3">

    <!-- BRAND -->
    <div class="brand text-white mb-4">
        <i class="fas fa-map-marked-alt text-primary"></i>
        visit<span class="text-primary">MERAUKE</span>
    </div>

    <!-- DASHBOARD -->
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        Dashboard
    </a>

    <div class="menu-title">Utama</div>

    <!-- WISATA -->
    <a href="{{ route('admin.tourism-places.index') }}"
       class="{{ request()->routeIs('admin.tourism-places.*') ? 'active' : '' }}">
        <i class="fas fa-mountain-sun"></i>
        Kelola Wisata
    </a>

    <!-- ARTIKEL -->
    <a href="{{ route('admin.articles.index') }}"
       class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
        <i class="fas fa-newspaper"></i>
        Kelola Artikel
    </a>

    <!-- TOKO -->
    <a href="{{ route('admin.toko.index') }}"
       class="{{ request()->routeIs('admin.toko.*') ? 'active' : '' }}">
        <i class="fas fa-store"></i>
        Kelola Toko
    </a>

    <!-- PRODUK -->
    <a href="{{ route('admin.produks.index') }}"
       class="{{ request()->routeIs('admin.produks.*') ? 'active' : '' }}">
        <i class="fas fa-box-open"></i>
        Kelola Produk
    </a>

    <!-- QUIZ -->
    <a href="{{ route('admin.quiz.index') }}"
       class="{{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i>
        Kelola Quiz
    </a>

    <!-- USER -->
    <a href="{{ route('admin.users.index') }}"
       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        Kelola User
    </a>

    <div class="menu-title">Master Data</div>

    <!-- FASILITAS -->
    <a href="{{ route('admin.facilities.index') }}"
       class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
        <i class="fas fa-building"></i>
        Fasilitas Wisata
    </a>

    <!-- KATEGORI -->
    <a href="{{ route('admin.categories.index') }}"
       class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        Kategori Wisata
    </a>

    <!-- SETTINGS -->
    <a href="{{ route('admin.settings.index') }}"
       class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        Settings
    </a>

    <hr class="text-secondary my-3">

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </form>

</div>