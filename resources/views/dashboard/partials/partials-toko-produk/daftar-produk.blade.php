{{-- DAFTAR PRODUK --}}
<div class="card shadow-sm rounded-4 p-0 border-0 overflow-hidden">
    <div class="p-4">
        <h5 class="fw-bold mb-3 text-center text-md-start" style="font-size: 0.95rem;">
            Daftar Produk Anda
        </h5>

        @if($produks->count())
            <div class="table-responsive" style="border-radius: 10px;">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr style="font-size: 0.8rem">
                            <th class="ps-3">No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $index => $p)
                            <tr style="font-size: 0.8rem">
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-semibold">
                                    <div
                                        style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $p->nama_produk }}
                                    </div>
                                </td>
                                <td class="text-nowrap">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td><span
                                        class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2">Tersedia</span>
                                </td>
                                <td class="text-nowrap">{{ $p->created_at->format('d M Y') }}</td>
                                <td class="text-center pe-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('produk.showByOwner', $p->id) }}"
                                            class="btn btn-outline-info btn-sm me-1" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-outline-warning btn-sm me-1"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('produk.destroy', $p->id) }}" method="POST"
                                            class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-delete"
                                                title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-5 text-center">
                <p class="text-muted mb-0">Belum ada produk yang ditambahkan.</p>
            </div>
        @endif
    </div>
</div>