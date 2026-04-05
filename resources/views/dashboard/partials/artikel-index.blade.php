<div class="container py-2">
    <div class="row g-4">

        {{-- KOLOM KANAN --}}
        <div class="col-lg-9">
            <div class="card shadow-sm rounded-4 p-4 h-100">

                <h5 class="fw-bold mb-3 text-center" style="font-size: 0.95rem;">
                    Daftar Artikel Anda
                </h5>

                @if($artikels->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr style="font-size: 0.8rem">
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($artikels as $index => $a)
                                    <tr style="font-size: 0.8rem">

                                        <td>{{ $index + 1 }}</td>

                                        <td class="fw-semibold">
                                            {{ \Illuminate\Support\Str::limit($a->judul, 40) }}
                                        </td>

                                        <td>
                                            <span class="badge bg-success text-white py-1 px-2">
                                                Publish
                                            </span>
                                        </td>

                                        <td>
                                            {{ $a->created_at->format('d M Y') }}
                                        </td>

                                        <td>
                                            {{-- LIHAT --}}
                                            <a href="{{ route('artikel.showByOwner', $a->slug) }}"
                                               class="btn btn-info btn-sm me-1"
                                               title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('artikel.edit', $a->id) }}"
                                               class="btn btn-warning btn-sm me-1"
                                               title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- DELETE --}}
                                            <form action="{{ route('artikel.destroy', $a->id) }}"
                                                  method="POST"
                                                  class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm btn-delete"
                                                        data-judul="{{ $a->judul }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">
                        Belum ada artikel
                    </p>
                @endif

            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="card shadow-sm rounded-4 p-4 h-100">

                <h5 class="fw-bold mb-3 text-center" style="font-size: 0.95rem;">
                    Kelola Artikel
                </h5>

                <ol class="list-group list-group-numbered" style="font-size: 0.8rem">
                    <li class="list-group-item">Klik tombol tulis artikel</li>
                    <li class="list-group-item">Isi judul & konten</li>
                    <li class="list-group-item">Tambahkan gambar</li>
                    <li class="list-group-item">Klik simpan</li>
                    <li class="list-group-item">Artikel langsung tampil</li>
                </ol>

                <div class="mt-4 text-center">
                    <a href="{{ route('artikel.create') }}"
                       class="btn btn-primary w-100"
                       style="font-size: 0.8rem">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tulis Artikel
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- ================= SWEETALERT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // KONFIRMASI DELETE DENGAN LOADING
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let judul = this.querySelector('.btn-delete').dataset.judul;

            Swal.fire({
                title: 'Yakin hapus?',
                text: "Artikel \"" + judul + "\" akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebelum submit
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus artikel',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // Submit form setelah loading muncul
                    form.submit();
                }
            });
        });
    });

    // NOTIFIKASI SUKSES
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
    @endif
</script>