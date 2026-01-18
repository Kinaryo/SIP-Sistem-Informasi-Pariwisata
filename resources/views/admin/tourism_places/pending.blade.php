@extends('admin.layouts.app-admin')

@section('title', 'Pending Wisata')
@section('page-title', 'Wisata Menunggu Verifikasi')

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
            <h5 class="fw-bold mb-0">Daftar Wisata Pending</h5>
            <a href="{{ route('admin.tourism-places.index') }}"
               class="btn btn-secondary btn-sm">
                Kembali
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Wisata</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Pengusul</th>
                        <th>Status</th>
                        <th width="240">Aksi</th>
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

                            <td>
                                {{ $place->location->city ?? '-' }},
                                {{ $place->location->province ?? '' }}
                            </td>

                            <td>
                                {{ $place->author->name ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $place->author->email ?? '' }}
                                </small>
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.tourism-places.edit', $place->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Review
                                </a>

                                <form action="{{ route('admin.tourism-places.verify', $place->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-success"
                                        onclick="return confirm('Verifikasi wisata ini?')">
                                        Verifikasi
                                    </button>
                                </form>

                                <form action="{{ route('admin.tourism-places.destroy', $place->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tolak & hapus wisata ini?')">
                                        Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada wisata pending
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
