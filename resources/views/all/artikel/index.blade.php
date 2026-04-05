@extends('all.layouts.app-all')

@section('title', 'Artikel')

@section('content')

<style>
    /*  Floating Button */
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
    }

    /*  Card */
    .card-hover {
        transition: 0.3s;
    }

    .card-hover:hover {
        transform: translateY(-5px);
    }

    /*  Judul limit */
    .title-limit {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Search Modern */
    .search-box {
        position: relative;
       
        margin: auto;
    }

    .search-box input {
        width: 100%;
        padding: 12px 45px 12px 45px;
        border-radius: 50px;
        border: 1px solid #ddd;
        background: #f8f9fa;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .search-box input:focus {
        background: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        outline: none;
    }

    .search-box .icon-left {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #888;
    }

    .search-box .icon-right {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #bbb;
        cursor: pointer;
        display: none;
    }

    .search-box .icon-right:hover {
        color: #dc3545;
    }
</style>

<section class="py-5 bg-light">
    <div class="container">

        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

            <!-- Header -->
            <div class="text-center ">
                <h2 class="fw-bold display-6">Artikel</h2>
                <p class="text-muted">
                    Temukan informasi menarik seputar wisata dan daerah
                </p>
            </div>

            <!-- Search -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="search-box">
                        <i class="fas fa-search icon-left"></i>

                        <input type="text"
                               id="search"
                               class="form-control"
                               placeholder="Cari artikel, judul, atau isi...">

                        <i class="fas fa-times icon-right" id="clearSearch"></i>
                    </div>
                </div>
            </div>

            <!--  List Artikel -->
            <div class="row g-4" id="artikel-list">
                @include('all.artikel.partials.list')
            </div>

        </div>
    </div>
</section>

<!-- ➕ Floating Button -->
<a href="{{ auth()->check() ? route('artikel.create') : route('login') }}"
   class="floating-btn"
   title="{{ auth()->check() ? 'Tulis Artikel' : 'Login dulu' }}">
    <i class="fas fa-plus"></i>
</a>

<!--  AJAX SEARCH -->
<script>
    let timeout = null;
    const searchInput = document.getElementById('search');
    const clearBtn = document.getElementById('clearSearch');

    searchInput.addEventListener('keyup', function () {

        clearTimeout(timeout);

        let value = this.value;

        // tampilkan tombol clear
        clearBtn.style.display = value ? 'block' : 'none';

        timeout = setTimeout(() => {

            fetch(`{{ route('artikel.index') }}?search=${value}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('artikel-list').innerHTML = data;
            });

        }, 400);
    });

    // ❌ Clear search
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        this.style.display = 'none';

        fetch(`{{ route('artikel.index') }}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById('artikel-list').innerHTML = data;
        });
    });
</script>

@endsection