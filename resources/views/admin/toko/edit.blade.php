@extends('admin.layouts.app-admin')

@section('title', 'Edit Toko')
@section('page-title', 'Manajemen Toko')

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
<div class="col-md-12 mx-auto">

<div class="card border-0 shadow-sm rounded-4 p-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Edit Toko</h5>
        <small class="text-muted">Perbarui informasi toko pengguna</small>
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

    <form id="formToko" action="{{ route('admin.toko.update', $toko->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- LOGO -->
            <div class="col-md-5">
                <label class="form-label">Logo Toko</label>
                <input type="file" name="logo" id="logoInput" class="form-control">

                @if($toko->logo)
                    <img src="{{ $toko->logo }}" id="preview" class="preview-img mt-3">
                @else
                    <img id="preview" class="preview-img mt-3 d-none">
                @endif
            </div>

            <!-- FORM -->
            <div class="col-md-7">

                <!-- USER (READONLY) -->
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control"
                        value="{{ $toko->user->name ?? '-' }} ({{ $toko->user->email ?? '-' }})"
                        disabled>
                </div>

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control"
                        value="{{ old('nama_toko', $toko->nama_toko) }}" required>
                </div>

                <!-- TELEPON -->
                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                        value="{{ old('telepon', $toko->telepon) }}">
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="editor"
                        class="form-control"
                        rows="4">{{ old('deskripsi', $toko->deskripsi) }}</textarea>
                </div>

                <!-- STATUS -->
                <div class="form-check mb-2">
                    <input type="checkbox" name="telepon_aktif"
                        class="form-check-input"
                        id="teleponAktif"
                        {{ $toko->telepon_aktif ? 'checked' : '' }}>
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
                        <span id="btnText">Update</span>
                        <span id="btnLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm"></span>
                            Mengupdate...
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

input.addEventListener('change', function (e) {

    const file = e.target.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
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
        title: 'Mengupdate Toko...',
        text: 'Perubahan data sedang diproses dan disimpan',
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