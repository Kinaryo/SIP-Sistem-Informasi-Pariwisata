@extends('admin.layouts.app-admin')

@section('title', 'Data Wisata')
@section('page-title', 'Manajemen Wisata')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <h5 class="fw-bold mb-0">Daftar Wisata</h5>
                <div>
                    <a href="{{ route('admin.tourism-places.pending') }}" class="btn btn-warning btn-sm">
                        Pending
                    </a>
                    <a href="{{ route('admin.tourism-places.create') }}" class="btn btn-primary btn-sm">
                        + Tambah
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th width="220">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($places as $place)
                            <tr>
                                <td>{{ $loop->iteration + $places->firstItem() - 1 }}</td>
                                <td>
                                    <strong>{{ $place->name }}</strong><br>
                                    <small class="text-muted">{{ $place->slug }}</small>
                                </td>
                                <td>{{ $place->category->name ?? '-' }}</td>
                                <td>{{ $place->location->city ?? '-' }}</td>
                                <td>{{ $place->author->name ?? '-' }}</td>
                                <td>
                                    @if ($place->is_verified)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif

                                    @if (!$place->is_active)
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.tourism-places.edit', $place->slug) }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.tourism-places.show', $place->slug) }}"
                                        class="btn btn-sm btn-info">
                                        View
                                    </a>


                                    @if (!$place->is_verified)
                                        <form action="{{ route('admin.tourism-places.verify', $place->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Verifikasi wisata ini?')">
                                                Verifikasi
                                            </button>
                                        </form>
                                    @endif

                                    @if ($place->is_active)
                                        <form action="{{ route('admin.tourism-places.deactivate', $place->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-secondary"
                                                onclick="return confirm('Nonaktifkan wisata ini?')">
                                                Nonaktif
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.tourism-places.activate', $place->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" onclick="return confirm('Aktifkan wisata ini?')">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.tourism-places.destroy', $place->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus wisata ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Data wisata belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $places->links() }}

        </div>
    </div>

@endsection