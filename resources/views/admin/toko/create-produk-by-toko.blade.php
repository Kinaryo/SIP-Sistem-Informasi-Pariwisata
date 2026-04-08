@extends('admin.layouts.app-admin')

@section('title', 'Tambah Produk Toko')
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
        <small class="text-muted">
            Tambah produk untuk toko: <strong>{{ $toko->nama_toko }}</strong>
        </small>
    </div>

    <!-- ERROR VALIDATION -->
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

    <!-- FORM -->
    <form id="formProduk"
          action="{{ route('admin.toko.produk.store', $toko->id) }}"
          method="POST"
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
                    <input type="text" name="nama_produk"
                        class="form-control"
                        value="{{ old('nama_produk') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga</label>
                    <input type="number" name="harga"
                        class="form-control"
                        value="{{ old('harga') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi"
                        id="editor"
                        class="form-control"
                        rows="5">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Verifikasi</label>
                        <select name="is_verified" class="form-select">
                            <option value="0">Belum</option>
                            <option value="1">Verified</option>
                        </select>
                    </div>
                </div>

                <!-- ACTION -->
                <div class="d-flex justify-content-between mt-4">

                    <!-- BACK JS -->
                    <button type="button"
                        onclick="history.back()"
                        class="btn btn-outline-secondary">
                        ← Kembali
                    </button>

                    <!-- SUBMIT -->
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        <span id="btnText">Simpan</span>
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

// ================= SUBMIT =================
const form = document.getElementById('formProduk');
const btnSubmit = document.getElementById('btnSubmit');
const btnText = document.getElementById('btnText');
const btnLoading = document.getElementById('btnLoading');

form.addEventListener('submit', function () {

    btnSubmit.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');

    Swal.fire({
        title: 'Menyimpan Produk...',
        html: `
            <div style="font-size:14px">
                Data produk sedang diproses.<br>
                Gambar akan diupload ke server.<br><br>
                <b>Mohon tunggu sebentar...</b>
            </div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

// ================= RESET BUTTON (JIKA ERROR BACK) =================
window.addEventListener('pageshow', function () {
    btnSubmit.disabled = false;
    btnText.classList.remove('d-none');
    btnLoading.classList.add('d-none');
});
</script>

<!-- ================= SUCCESS ================= -->
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil 🎉',
    html: `
        <div style="font-size:14px">
            Produk berhasil ditambahkan ke toko.<br><br>
            <b>Data sudah tersimpan dengan aman.</b>
        </div>
    `,
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location.href = "{{ route('admin.toko.show', $toko->id) }}";
});
</script>
@endif

<!-- ================= ERROR ================= -->
@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal ❌',
    html: `
        <div style="font-size:14px">
            Terjadi kesalahan saat menyimpan produk.<br><br>
            <b>Kemungkinan penyebab:</b>
            <ul style="text-align:left">
                <li>Koneksi internet terputus</li>
                <li>Upload gambar gagal</li>
                <li>Server sedang bermasalah</li>
            </ul>
            Silakan coba lagi.
        </div>
    `
});
</script>
@endif

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => console.error(error));
</script>

@endpush