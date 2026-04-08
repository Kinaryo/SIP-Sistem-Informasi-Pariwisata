@extends('all.layouts.app-all')

@section('title', 'Kontak')

@section('content')

    <section id="kontak" class="py-5 bg-light">
        <div class="container">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

                <!-- HEADER -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold display-6">Kontak Kami</h2>
                    <p class="text-muted">
                        Hubungi tim VisitMerauke untuk informasi, kerja sama, atau pertanyaan
                    </p>
                </div>

                <div class="row g-4">

                    <!-- FORM -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Hubungi Kami</h6>

                        <form id="contactForm" action="{{ route('kontak.kirim') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                            </div>

                            <div class="col-12">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="4" placeholder="Tulis pesan Anda..."
                                    required></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- MAP -->
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Lokasi Kami</h6>

                        <div class="ratio ratio-4x3 rounded overflow-hidden">
                            <iframe
                                src="https://www.google.com/maps?q={{ $setting->latitude ?? '-8.4936' }},{{ $setting->longitude ?? '140.4016' }}&z=15&output=embed"
                                style="border:0;" loading="lazy">
                            </iframe>
                        </div>

                        <small class="text-muted d-block mt-2">
                            {{ $setting->office_name ?? 'Merauke – Papua Selatan' }}
                        </small>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ================= SWEETALERT ================= -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('contactForm');

            // LOADING SAAT SUBMIT
            if (form) {
                form.addEventListener('submit', function () {
                    Swal.fire({
                        title: 'Mengirim pesan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }

        });
    </script>

    <!-- ================= NOTIFIKASI ================= -->

    {{-- SUCCESS --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                document.getElementById('contactForm').reset();
            });
        </script>
    @endif

    {{--  ERROR SERVER --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

    {{-- VALIDASI --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Periksa Input!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

@endsection