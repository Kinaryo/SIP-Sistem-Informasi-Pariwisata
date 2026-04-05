@extends('all.layouts.app-all')

@section('title', 'Produk')

@section('content')

<style>
    /* Floating Button */
    .floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 55px;
        height: 55px;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 999;
        transition: 0.3s;
        text-decoration: none;
    }

    .floating-btn:hover {
        transform: scale(1.1);
        background-color: #0b5ed7;
        color: #fff;
    }

    /* Card */
    .card-hover {
        transition: 0.3s;
    }

    .card-hover:hover {
        transform: translateY(-5px);
    }

    /* Limit nama */
    .title-limit {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Search */
    .search-box {
        position: relative;
        margin: auto;
    }

    .search-box input {
        width: 100%;
        padding: 12px 45px;
        border-radius: 50px;
        border: 1px solid #ddd;
        background: #f8f9fa;
        transition: 0.2s;
    }

    .search-box input:focus {
        background: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        outline: none;
    }

    .search-box i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .icon-left {
        left: 15px;
        color: #888;
    }

    .icon-right {
        right: 15px;
        cursor: pointer;
        display: none;
        color: #bbb;
    }

    .icon-right:hover {
        color: red;
    }
</style>

<section class="py-5 bg-light">
<div class="container">

<div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

    <!-- HEADER -->
    <div class="text-center mb-4">
        <h2 class="fw-bold display-6">Produk</h2>
        <p class="text-muted mb-0">Temukan produk unggulan daerah</p>
    </div>

    <!-- SEARCH -->
    <div class="mb-4">
        <div class="search-box">
            <i class="bi bi-search icon-left"></i>

            <input type="text" id="search" placeholder="Cari produk...">

            <i class="bi bi-x icon-right" id="clearSearch"></i>
        </div>
    </div>

    <!-- LIST PRODUK -->
    <div class="row g-4" id="produk-list">
        @include('all.produk.partials.list')
    </div>

</div>
</div>
</section>

<!-- FLOATING BUTTON -->
<a href="{{ auth()->check() ? route('produk.create') : route('login') }}"
   class="floating-btn"
   title="{{ auth()->check() ? 'Tambah Produk' : 'Login dulu' }}">
    <i class="bi bi-plus"></i>
</a>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- AJAX SEARCH -->
<script>
let timeout = null;
const searchInput = document.getElementById('search');
const clearBtn = document.getElementById('clearSearch');

searchInput.addEventListener('keyup', function () {

    clearTimeout(timeout);

    let value = this.value;
    clearBtn.style.display = value ? 'block' : 'none';

    timeout = setTimeout(() => {

        fetch(`{{ route('produk.index') }}?search=${value}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById('produk-list').innerHTML = data;
        });

    }, 400);
});

// CLEAR SEARCH
clearBtn.addEventListener('click', function () {
    searchInput.value = '';
    this.style.display = 'none';

    fetch(`{{ route('produk.index') }}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('produk-list').innerHTML = data;
    });
});
</script>

@endsection