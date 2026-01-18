@extends('all.layouts.app-all')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Dashboard</h2>
            <p class="text-muted">Selamat datang di dashboard Anda! Berikut adalah langkah-langkah untuk menambahkan tempat
                wisata di area Anda:</p>
        </div>

        <div class="row g-4">
            {{-- Kolom Kanan: Daftar Tempat Wisata --}}
            <div class="col-lg-9">
                <div class="card shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-3" style="font-size: 0.95rem; text-align: center;">Daftar Tempat Wisata Anda</h5>

                    @if(isset($tourismPlaces) && $tourismPlaces->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr style="font-size: 0.8rem">
                                        <th>No</th>
                                        <th>Nama Tempat</th>
                                        <th>Kota/Kabupaten</th>
                                        <th>Status</th>
                                        <th>Tanggal Submit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tourismPlaces as $index => $place)
                                        <tr style="font-size: 0.8rem">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $place->name }}</td>
                                            <td>{{ $place->location->city ?? '-' }}</td>
                                            <td>
                                                @if($place->is_verified && $place->is_active)
                                                    <span class="badge bg-success text-white py-1 px-2 d-inline-block text-truncate"
                                                        style="max-width: 120px;">
                                                        Aktif
                                                    </span>
                                                @elseif($place->is_verified && !$place->is_active)
                                                    <span class="badge bg-warning text-dark py-1 px-2 d-inline-block text-truncate"
                                                        style="max-width: 120px;">
                                                        Menunggu Aktivasi
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary text-white py-1 px-2 d-inline-block text-truncate"
                                                        style="max-width: 120px;">
                                                        Menunggu Verifikasi
                                                    </span>
                                                @endif
                                            </td>

                                            <td>{{ $place->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.showTourismPlaces', $place->slug) }}"
                                                    class="btn btn-info btn-sm me-1" title="Lihat">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('dashboard.editTourismPlaces', $place->slug) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Anda belum menambahkan tempat wisata apapun.</p>
                    @endif
                </div>
            </div>
            {{-- Kolom Kiri: Alur Menambahkan Tempat Wisata --}}
            <div class="col-lg-3">
                <div class="card shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-3" style="font-size: 0.95rem; text-align: center;">Alur Menambahkan Tempat Wisata
                    </h5>
                    <ol class="list-group list-group-numbered" style="font-size: 0.8rem">
                        <li class="list-group-item">Login terlebih dahulu</li>
                        <li class="list-group-item">Klik tombol <strong>"Tambah Tempat Wisata"</strong> di bawah ini.</li>
                        <li class="list-group-item">Isi formulir dengan lengkap: nama tempat, deskripsi, kategori, alamat,
                            foto, dan
                            informasi lainnya.</li>
                        <li class="list-group-item">Setelah selesai, klik <strong>"Simpan"</strong>. Tempat wisata akan
                            masuk ke
                            database dan menunggu approval admin (jika ada).</li>
                        <li class="list-group-item">Tempat wisata yang berhasil ditambahkan akan muncul di daftar tempat
                            wisata
                            Anda.</li>
                    </ol>

                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard.createTourismPlaces') }}" class="btn btn-primary rounded px-2 py-2"
                            style="font-size: 0.8rem">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Tempat Wisata
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection