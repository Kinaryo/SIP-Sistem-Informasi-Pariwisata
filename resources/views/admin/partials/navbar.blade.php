<nav class="navbar bg-white shadow-sm rounded px-4 py-3">
    <div class="d-flex justify-content-between w-100 align-items-center">

        <h6 class="mb-0 fw-semibold">
            @yield('page-title', 'Dashboard')
        </h6>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                {{ auth()->user()->name }}
            </span>
            <i class="fas fa-user-circle fa-lg text-secondary"></i>
        </div>

    </div>
</nav>