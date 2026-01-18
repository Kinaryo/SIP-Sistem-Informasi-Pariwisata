@extends('all.layouts.app-all')

@section('title', 'Kontak')

@section('content')

    <section id="kontak" class="py-5 bg-light">
        <div class="container">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

                <div class="text-center mb-5">
                    <h2 class="fw-bold display-6">Kontak Kami</h2>
                    <p class="text-muted">
                        Hubungi kami atau kunjungi langsung kantor kami
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Hubungi Kami</h6>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('kontak.kirim') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                            </div>

                            <div class="col-12">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="4" placeholder="Pesan"
                                    required></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Lokasi Kami</h6>
                        <div class="ratio ratio-4x3 rounded overflow-hidden">
                            {{-- Gunakan latitude & longitude dari $setting --}}
                            <iframe 
                                src="https://www.google.com/maps?q={{ $setting->latitude ?? '-8.4936' }},{{ $setting->longitude ?? '140.4016' }}&z=15&output=embed" 
                                style="border:0;" loading="lazy">
                            </iframe>
                        </div>
                        <small class="text-muted d-block mt-2">
                            📍 {{ $setting->office_name ?? 'Merauke – Papua Selatan' }}
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
