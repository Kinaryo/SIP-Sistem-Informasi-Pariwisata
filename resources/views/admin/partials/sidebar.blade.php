<div class="sidebar position-fixed p-3">

    <h5 class="text-white fw-bold mb-4">
        <i class="fas fa-map-marked-alt text-primary"></i>
        JELAJAHI<span class="text-primary">.My.ID</span>
    </h5>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line me-2"></i>
        Dashboard
    </a>

    <a href="{{ route('admin.tourism-places.index') }}"
        class="{{ request()->routeIs('admin.tourism-places.*') ? 'active' : '' }}">
        <i class="fas fa-mountain-sun me-2"></i>
        Destinasi
    </a>
    <a href="{{ route('admin.quiz.index') }}" class="{{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
        <i class="fas fa-question-circle me-2"></i>
        Kelola Quiz
    </a>

    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i>
        Kelola User
    </a>


    <!-- Menu baru -->
    <hr class="text-secondary">


    <a href="{{ route('admin.facilities.index') }}"
        class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
        <i class="fas fa-building me-2"></i>
        Kelola Fasilitas
    </a>
    <a href="{{ route('admin.categories.index') }}"
        class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="fas fa-tags me-2"></i>
        Daftar Kategori
    </a>

    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fas fa-cog me-2"></i>
        Settings
    </a>

    <hr class="text-secondary">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-sm btn-outline-light w-100">
            <i class="fas fa-sign-out-alt me-2"></i>
            Logout
        </button>
    </form>

</div>