@extends('all.layouts.app-all')

@section('title', $artikel->judul)

@section('content')

    <style>
        /* Konten artikel */
        .article-content p {
            margin-bottom: 1rem;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 10px 0;
            object-fit: cover;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .article-content ul {
            padding-left: 20px;
        }

        .article-content strong {
            font-weight: 600;
        }

        /* Gambar full di atas */
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 16px;
            text-align: center;
            gap: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .placeholder-img i {
            font-size: 48px;
        }

        /* Author box */
        .author-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #e9f2ff;
            border-radius: 12px;
        }

        .author-box .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .author-box .details {
            font-size: 0.95rem;
        }

        .author-box .details .name {
            font-weight: 600;
        }

        .author-box .details .date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Action buttons */
        .action-buttons {
            margin-bottom: 20px;
        }
    </style>

    <section class="py-5 bg-light">
        <div class="container">

            <!-- HEADER -->
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6">Detail Artikel</h2>
            </div>

            <!-- CARD -->
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <!-- GAMBAR FULL -->
                @if($artikel->gambar)
                    <img src="{{ Str::startsWith($artikel->gambar, 'http') ? $artikel->gambar : asset('storage/' . $artikel->gambar) }}"
                        class="">
                @else
                    <div class="placeholder-img">
                        <i class="fas fa-image"></i>
                        <span>Tidak ada gambar</span>
                    </div>
                @endif

                <!-- JUDUL -->
                <h2 class="fw-bold mb-3">{{ $artikel->judul }}</h2>

                <!-- AUTHOR BOX -->
                <div class="author-box">
                    <div class="avatar">{{ strtoupper(substr($artikel->user->name ?? 'A', 0, 1)) }}</div>
                    <div class="details">
                        <div class="name">{{ $artikel->user->name ?? 'Admin' }}</div>
                        <div class="date">{{ $artikel->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <!-- ISI ARTIKEL -->
                <div class="text-dark article-content" style="line-height:1.8;">
                    {!! $artikel->isi !!}
                </div>

                <!-- TOMBOL KEMBALI & ACTION BUTTONS -->
                <div class="d-flex justify-content-between mt-4 align-items-center">

                    <!-- Tombol Kembali -->
                    <button onclick="history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>

                    <!-- Tombol Edit & Hapus (hanya untuk penulis) -->
                    <div class="action-buttons d-flex gap-2">
                        @if(auth()->id() == $artikel->user_id)
                            <!-- Edit -->
                            <a href="{{ route('artikel.edit', $artikel->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <!-- Hapus dengan SweetAlert -->
                            <form id="formDelete" action="{{ route('artikel.destroy', $artikel->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" id="btnDelete">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const formDelete = document.getElementById('formDelete');

if (formDelete) {
    formDelete.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Artikel akan dihapus permanen dan tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                // LOADING
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses artikel',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(formDelete.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: new FormData(formDelete)
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(res => {

                    Swal.close();

                    if (res.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.body.message || 'Artikel berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('artikel.index') }}";
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.body.message || 'Gagal menghapus artikel'
                        });
                    }

                })
                .catch(() => {

                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan jaringan / server'
                    });

                });
            }
        });
    });
}

// ================= SUCCESS & ERROR (fallback jika redirect biasa) =================
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    timer: 1500,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}"
});
@endif
</script>

@endsection