@extends('admin.layouts.app-admin')

@section('title', 'Tambah Toko')
@section('page-title', 'Manajemen Toko')

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
    gap: 8px;
}

.placeholder-img i {
    font-size: 36px;
}
</style>

<div class="row">
<div class="col-md-12 mx-auto">

<div class="card border-0 shadow-sm rounded-4 p-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Tambah Toko</h5>
        <small class="text-muted">Buat toko baru untuk pengguna</small>
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

    <form id="formToko" action="{{ route('admin.toko.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <!-- LOGO -->
            <div class="col-md-5">
                <label class="form-label">Logo Toko</label>
                <input type="file" name="logo" id="logoInput" class="form-control">

                <div id="placeholder" class="placeholder-img mt-3">
                    <i class="bi bi-image"></i>
                    <span>Belum ada logo</span>
                </div>

                <img id="preview" class="preview-img mt-3">
            </div>

            <!-- FORM -->
            <div class="col-md-7">

                <!-- USER -->
                <div class="mb-3">
                    <label class="form-label">Pilih User</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control"
                        value="{{ old('nama_toko') }}"
                        placeholder="Masukkan nama toko..." required>
                </div>

                <!-- TELEPON -->
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                        value="{{ old('telepon') }}"
                        placeholder="Contoh: 08123456789">
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="editor"
                        class="form-control"
                        rows="4">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="form-check mb-2">
                    <input type="checkbox" name="telepon_aktif" class="form-check-input" id="teleponAktif">
                    <label class="form-check-label" for="teleponAktif">
                        Aktifkan Telepon
                    </label>
                </div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-between mt-4">

                    <a href="{{ route('admin.toko.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

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

// ================= PREVIEW LOGO =================
const input = document.getElementById('logoInput');
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
const form = document.getElementById('formToko');
const btnSubmit = document.getElementById('btnSubmit');
const btnText = document.getElementById('btnText');
const btnLoading = document.getElementById('btnLoading');

form.addEventListener('submit', function () {

    btnSubmit.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');

    Swal.fire({
        title: 'Menyimpan Toko...',
        text: 'Data toko sedang diproses dan disimpan ke sistem',
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
    window.location.href = "{{ route('admin.toko.index') }}";
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