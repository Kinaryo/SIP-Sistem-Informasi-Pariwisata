@extends('admin.layouts.app-admin')

@section('title', 'Edit Produk')
@section('page-title', 'Manajemen Produk')

@section('content')

    <style>
        .preview-img {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 mx-auto">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Edit Produk</h5>
                    <small class="text-muted">Perbarui data produk dengan benar</small>
                </div>

                <!-- ERROR -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formProduk" action="{{ route('admin.produks.update', $produk->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <!-- FOTO -->
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Foto Produk</label>
                            <input type="file" name="foto" class="form-control" id="fotoInput">

                            @if($produk->foto)
                                                <img src="{{ Str::startsWith($produk->foto, 'http')
                                ? $produk->foto
                                : asset('storage/' . $produk->foto) }}" class="preview-img mt-3" id="preview">
                            @else
                                <img id="preview" class="preview-img mt-3 d-none" />
                            @endif
                        </div>

                        <!-- FORM -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga</label>
                                <input type="number" name="harga" class="form-control"
                                    value="{{ old('harga', $produk->harga) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" id="editor" class="form-control"
                                    rows="5">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            </div>

                            <!-- STATUS -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Produk</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ $produk->is_active ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ !$produk->is_active ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <small class="text-muted">Produk aktif akan tampil di publik</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Verifikasi</label>
                                    <select name="is_verified" class="form-select">
                                        <option value="0" {{ !$produk->is_verified ? 'selected' : '' }}>Belum</option>
                                        <option value="1" {{ $produk->is_verified ? 'selected' : '' }}>Verified</option>
                                    </select>
                                    <small class="text-muted">Admin Wajib Memverifikasi Produk</small>
                                </div>
                            </div>

                            <!-- ACTION -->
                            <div class="d-flex justify-content-between mt-4">

                                <a href="javascript:void(0)"
                                    onclick="document.referrer ? window.history.back() : window.location.href='{{ route('admin.toko.index') }}'"
                                    class="btn btn-outline-secondary">
                                    ← Kembali
                                </a>

                                <button type="submit" id="btnSubmit" class="btn btn-primary">
                                    <span id="btnText">Update Produk</span>
                                    <span id="btnLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm"></span>
                                        Memproses...
                                    </span>
                                </button>

                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection


@push('scripts')

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

        // ================= SUBMIT LOADING =================
        const form = document.getElementById('formProduk');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        form.addEventListener('submit', function () {

            btnSubmit.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');

            Swal.fire({
                title: 'Memperbarui Produk...',
                text: 'Perubahan sedang disimpan ke sistem, mohon tunggu.',
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
                timer: 1800,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ route('admin.produks.index') }}";
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

@endpush