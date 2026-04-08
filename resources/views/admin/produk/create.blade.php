@extends('admin.layouts.app-admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Manajemen Produk')

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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 15px;
            text-align: center;
            padding: 10px;
            gap: 8px;
        }

        .placeholder-img i {
            font-size: 36px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 mx-auto">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">Tambah Produk</h5>
                    <small class="text-muted">Isi data produk dengan lengkap dan benar</small>
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
                <!-- INFO ADMIN -->
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-start gap-3">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <div>
                        <strong>Informasi:</strong><br>
                        Halaman ini digunakan untuk menambahkan produk oleh <b>Admin</b>.<br>
                        Produk yang ditambahkan di sini akan menjadi milik <b>Admin (Owner)</b>.<br>
                        Jika ingin menambahkan produk ke <b>Toko tertentu</b>, silakan masuk melalui menu
                        <b>Kelola Toko → Detail Toko → Tambah Produk</b>.
                    </div>
                </div>
                <form id="formProduk" action="{{ route('admin.produks.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">

                        <!-- FOTO -->
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Foto Produk</label>
                            <input type="file" name="foto" class="form-control" id="fotoInput">

                            <div id="placeholder" class="placeholder-img mt-3">
                                <i class="bi bi-image"></i>
                                <span>Belum ada gambar</span>
                            </div>

                            <img id="preview" class="preview-img mt-3" />
                        </div>

                        <!-- FORM -->
                        <div class="col-md-7">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control"
                                    value="{{ old('nama_produk') }}" placeholder="Contoh: Keripik Sagu Papua" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Harga</label>
                                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}"
                                    placeholder="Contoh: 15000" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" id="editor" class="form-control" rows="5">{{ old('deskripsi') }}</textarea>
                            </div>

                            <!-- STATUS -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Status Produk</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif
                                        </option>
                                    </select>
                                    <small class="text-muted">Produk aktif akan tampil di publik</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Verifikasi</label>
                                    <select name="is_verified" class="form-select">
                                        <option value="0" {{ old('is_verified') == '0' ? 'selected' : '' }}>Belum
                                        </option>
                                        <option value="1" {{ old('is_verified') == '1' ? 'selected' : '' }}>Verified
                                        </option>
                                    </select>
                                    <small class="text-muted">Admin Wajib Memverifikasi Produk </small>
                                </div>
                            </div>

                            <!-- ACTION -->
                            <div class="d-flex justify-content-between mt-4">

                                <a href="{{ route('admin.produks.index') }}" class="btn btn-outline-secondary">
                                    ← Kembali
                                </a>

                                <button type="submit" id="btnSubmit" class="btn btn-primary">
                                    <span id="btnText">Simpan Produk</span>
                                    <span id="btnLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm"></span>
                                        Menyimpan...
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
        const placeholder = document.getElementById('placeholder');

        input.addEventListener('change', function(e) {
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

        // ================= SUBMIT LOADING =================
        const form = document.getElementById('formProduk');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        form.addEventListener('submit', function() {

            btnSubmit.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');

            Swal.fire({
                title: 'Menyimpan Produk...',
                text: 'Data sedang diproses dan diupload ke server.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // ================= SUCCESS =================
        @if (session('success'))
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
        @if (session('error'))
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
