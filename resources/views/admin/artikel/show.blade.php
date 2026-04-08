@extends('admin.layouts.app-admin')

@section('title', $artikel->judul)
@section('page-title', 'Manajemen Artikel')

@section('content')

<style>
.article-content p { margin-bottom: 1rem; }
.article-content img {
    max-width: 100%;
    border-radius: 10px;
    margin: 10px 0;
}
.artikel-img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 20px;
}
.placeholder-img {
    width: 100%;
    max-height: 400px;
    border: 2px dashed #dee2e6;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    padding: 20px;
    margin-bottom: 20px;
}
.author-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 10px 15px;
    background-color: #e9f2ff;
    border-radius: 12px;
}
.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="row">
<div class="col-md-12 mx-auto">

<div class="card border-0 shadow-lg rounded-4 p-4">
    <h5 class="fw-bold mb-4">Detail Artikel</h5>

    <!-- GAMBAR -->
    @if($artikel->gambar)
        <img src="{{ $artikel->gambar }}" class="artikel-img">
    @else
        <div class="placeholder-img">
            <i class="bi bi-image fs-1"></i>
        </div>
    @endif

    <!-- JUDUL -->
    <h3 class="fw-bold mb-3">{{ $artikel->judul }}</h3>

    <!-- AUTHOR -->
    <div class="author-box">
        <div class="avatar">
            {{ strtoupper(substr($artikel->user->name ?? 'A',0,1)) }}
        </div>
        <div>
            <div class="fw-semibold">{{ $artikel->user->name ?? 'Admin' }}</div>
            <small class="text-muted">{{ $artikel->created_at->format('d M Y') }}</small>
        </div>
    </div>

    <!-- ISI -->
    <div class="article-content">
        {!! $artikel->isi !!}
    </div>

    <!-- ACTION -->
    <div class="d-flex justify-content-between mt-4">

        <!-- BACK -->
        <a href="{{ route('admin.articles.index') }}" 
           class="btn btn-outline-secondary"
           data-bs-toggle="tooltip" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>

       <div class="d-flex gap-2">

    <!-- EDIT -->
    <a href="{{ route('admin.articles.edit', $artikel->id) }}"
       class="btn btn-warning"
       data-bs-toggle="tooltip" title="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- DELETE -->
    <button class="btn btn-danger"
            onclick="deleteArtikel({{ $artikel->id }})"
            data-bs-toggle="tooltip" title="Hapus">
        <i class="bi bi-trash"></i>
    </button>

</div>

    </div>

</div>
</div>
</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>
// TOOLTIP
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

// DELETE
function deleteArtikel(id){
    Swal.fire({
        title: 'Hapus artikel?',
        text: 'Data akan dihapus permanen dan tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if(result.isConfirmed){

            // LOADING
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus artikel, mohon tunggu',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/articles/${id}`, {
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(async res => {
                let data = await res.json();

                if(!res.ok){
                    throw new Error(data.message || 'Gagal menghapus');
                }

                return data;
            })
            .then(res => {
                // SUCCESS
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('admin.articles.index') }}";
                });
            })
            .catch(err => {
                // ERROR
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message,
                    confirmButtonText: 'Coba lagi'
                });
            });
        }
    });
}
</script>

@endpush