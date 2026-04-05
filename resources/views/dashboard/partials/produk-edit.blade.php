@extends('all.layouts.app-all')

@section('title', 'Edit Produk')

@section('content')

<style>
    .preview-img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 12px;
    }
</style>

<section class="py-5 bg-light">
    <div class="container">

        <!-- HEADER -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Edit Produk</h2>
            <p class="text-muted">
                Perbarui informasi produk kamu
            </p>
        </div>

        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

            <!-- ERROR VALIDATION -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formProduk" action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- FOTO -->
                    <div class="col-md-5">
                        <label class="form-label">Foto Produk</label>
                        <input type="file" name="foto" class="form-control" id="fotoInput">

                        <!-- Preview Lama -->
                        @if($produk->foto)
                            <img src="{{ Str::startsWith($produk->foto, 'http')
                                ? $produk->foto
                                : asset('storage/' . $produk->foto) }}"
                                class="preview-img mt-3" id="preview">
                        @else
                            <img id="preview" class="preview-img mt-3 d-none" />
                        @endif
                    </div>

                    <!-- FORM -->
                    <div class="col-md-7">

                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control"
                                value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control"
                                value="{{ old('harga', $produk->harga) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="editor" class="form-control"
                                rows="5">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">

                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </button>

                            <!-- BUTTON LOADING -->
                            <button type="submit" id="btnSubmit" class="btn btn-primary">
                                <span id="btnText">Update Produk</span>
                                <span id="btnLoading" class="d-none">
                                    <span class="spinner-border spinner-border-sm"></span>
                                    Loading...
                                </span>
                            </button>

                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ================= PREVIEW GAMBAR =================
    const input = document.getElementById('fotoInput');
    const preview = document.getElementById('preview');

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });

    // ================= LOADING SUBMIT =================
    const form = document.getElementById('formProduk');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');

    form.addEventListener('submit', function () {

        btnSubmit.disabled = true;

        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');

        Swal.fire({
            title: 'Mengupdate...',
            text: 'Sedang memperbarui data produk',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });

    // ================= SUCCESS =================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "/dashboard?tab=produk";
        });
    @endif

    // ================= ERROR =================
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}"
        });
    @endif
</script>

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => console.error(error));
</script>

@endsection