@extends('all.layouts.app-all')

@section('title', 'Tambah Produk')

@section('content')

<style>
    .preview-img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
        border-radius: 12px;
        display: none;
    }
    .placeholder-img {
        width: 100%;
        max-height: 250px;
        border: 2px dashed #ccc;
        border-radius: 12px;
        display: flex;
        flex-direction: column; /* icon di atas, teks di bawah */
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 16px;
        text-align: center;
        padding: 10px;
        gap: 8px;
    }
    .placeholder-img i {
        font-size: 36px;
    }
</style>

<section class="py-5 bg-light">
    <div class="container">

        <!-- HEADER -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Tambah Produk</h2>
            <p class="text-muted">
                Tambahkan produk unggulan daerah kamu
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

            <form id="formProduk" action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">

                    <!-- FOTO -->
                    <div class="col-md-5">
                        <label class="form-label">Foto Produk</label>
                        <input type="file" name="foto" class="form-control" id="fotoInput">
                        
                        <div id="placeholder" class="placeholder-img mt-3">
                            <i class="bi bi-image"></i>
                            <span>Gambar belum ditambahkan</span>
                        </div>
                        <img id="preview" class="preview-img mt-3" />
                    </div>

                    <!-- FORM -->
                    <div class="col-md-7">

                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control"
                                value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control"
                                value="{{ old('harga') }}" placeholder="Masukkan harga..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="editor" class="form-control"
                                rows="5">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">

                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                                ← Kembali
                            </button>

                            <!-- BUTTON LOADING -->
                            <button type="submit" id="btnSubmit" class="btn btn-primary">
                                <span id="btnText">Simpan Produk</span>
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
    const placeholder = document.getElementById('placeholder');

    input.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
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
            title: 'Mengupload...',
            text: 'Sedang mengirim data produk',
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