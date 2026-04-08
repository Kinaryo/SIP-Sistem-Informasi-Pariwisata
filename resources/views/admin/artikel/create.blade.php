@extends('admin.layouts.app-admin')

@section('title', 'Tambah Artikel')
@section('page-title', 'Manajemen Artikel')

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
    padding: 10px;
    gap: 8px;
}
.placeholder-img i {
    font-size: 36px;
}
</style>

<div class="row">
<div class="col-md-12 mx-auto">

<div class="card border-0 shadow-lg rounded-4 p-4">

    <h5 class="fw-bold mb-3">Form Tambah Artikel</h5>

    <!-- VALIDASI -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formArtikel" action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            <!-- GAMBAR -->
            <div class="col-md-5">
                <label class="form-label">Gambar</label>
                <input type="file" name="gambar" class="form-control" id="gambarInput">

                <div id="placeholder" class="placeholder-img mt-3">
                    <i class="bi bi-image"></i>
                    <span>Belum ada gambar</span>
                </div>

                <img id="preview" class="preview-img mt-3" />
            </div>

            <!-- FORM -->
            <div class="col-md-7">

                <div class="mb-3">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control"
                        value="{{ old('judul') }}" required>
                </div>

                <div class="mb-3">
                    <label>Isi Artikel</label>
                    <textarea name="isi" id="editor" class="form-control">{{ old('isi') }}</textarea>
                </div>

                <div class="d-flex justify-content-between mt-4">

                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <button class="btn btn-primary" id="btnSubmit">
                        <span id="btnText">
                            <i class="bi bi-save"></i> Simpan
                        </span>
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
</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>
// PREVIEW GAMBAR
const input = document.getElementById('gambarInput');
const preview = document.getElementById('preview');
const placeholder = document.getElementById('placeholder');

input.addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        preview.style.display = 'none';
        placeholder.style.display = 'flex';
    }
});

// LOADING
const form = document.getElementById('formArtikel');
const btnSubmit = document.getElementById('btnSubmit');
const btnText = document.getElementById('btnText');
const btnLoading = document.getElementById('btnLoading');

form.addEventListener('submit', function(){
    btnSubmit.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');

    Swal.fire({
        title: 'Menyimpan...',
        text: 'Sedang memproses artikel',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
});

// SUCCESS
@if(session('success'))
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:"{{ session('success') }}",
    timer:1500,
    showConfirmButton:false
}).then(()=>{
    window.location.href = "{{ route('admin.articles.index') }}";
});
@endif

// ERROR
@if(session('error'))
Swal.fire({
    icon:'error',
    title:'Gagal',
    text:"{{ session('error') }}"
});
@endif
</script>

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector('#editor'))
.catch(error => console.error(error));
</script>

@endpush