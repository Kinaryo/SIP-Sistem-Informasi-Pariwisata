@extends('all.layouts.app-all')

@section('title', 'Kebijakan Privasi')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mb-5">
                    <h1 class="fw-bold display-5 text-dark">Kebijakan Privasi</h1>
                    <p class="text-muted lead">Terakhir diperbarui: {{ date('d F Y') }}</p>
                    <div class="mx-auto mt-3" style="width: 60px; height: 4px; background-color: #0d6efd; border-radius: 2px;"></div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="mb-5">
                            <p class="fs-5 text-secondary leading-relaxed">
                                Kami menghargai privasi Anda. Halaman ini menjelaskan bagaimana kami mengumpulkan, 
                                menggunakan, dan melindungi informasi pengguna di website ini dengan transparansi penuh.
                            </p>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="fw-bold d-flex align-items-center text-primary">
                                    <span class="badge bg-primary-subtle text-primary me-2 px-2 py-1">1</span> 
                                    Informasi yang Kami Kumpulkan
                                </h5>
                                <div class="ps-4 mt-3">
                                    <ul class="list-group list-group-flush border-start border-2 ms-2">
                                        <li class="list-group-item border-0 bg-transparent py-1">Nama dan email (melalui form kontak)</li>
                                        <li class="list-group-item border-0 bg-transparent py-1">Data penggunaan website (melalui cookies)</li>
                                        <li class="list-group-item border-0 bg-transparent py-1">Informasi perangkat dan browser</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="fw-bold d-flex align-items-center text-primary">
                                    <span class="badge bg-primary-subtle text-primary me-2 px-2 py-1">2</span> 
                                    Penggunaan Informasi
                                </h5>
                                <div class="ps-4 mt-3">
                                    <p class="text-muted">Informasi Anda kami gunakan secara bertanggung jawab untuk:</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">Peningkatan Layanan</span>
                                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">Respon Pesan</span>
                                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">Personalisasi Konten</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="h-100 p-4 rounded-3 bg-light border-start border-primary border-4">
                                    <h6 class="fw-bold">3. Cookies</h6>
                                    <p class="small text-muted mb-0">Website ini menggunakan cookies untuk meningkatkan pengalaman pengguna. Dengan tetap menjelajah, Anda menyetujui penggunaannya.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="h-100 p-4 rounded-3 bg-light border-start border-warning border-4">
                                    <h6 class="fw-bold">4. Iklan Pihak Ketiga</h6>
                                    <p class="small text-muted mb-0">Kami menggunakan Google AdSense yang dapat menyimpan cookie untuk menyajikan iklan yang relevan berdasarkan minat Anda.</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="my-4 opacity-25">
                                <h5 class="fw-bold text-dark">5. Keamanan Data</h5>
                                <p class="text-muted">Kami menerapkan standar keamanan industri untuk melindungi data Anda. Namun, perlu diingat bahwa tidak ada transmisi data internet yang dijamin aman 100%.</p>
                                
                                <h5 class="fw-bold text-dark mt-4">6. Persetujuan</h5>
                                <p class="text-muted">Dengan mengakses dan menggunakan layanan kami, Anda secara otomatis menyatakan setuju dengan seluruh poin dalam Kebijakan Privasi ini.</p>

                                <h5 class="fw-bold text-dark mt-4">7. Perubahan Kebijakan</h5>
                                <p class="text-muted mb-0">Kami berhak memperbarui kebijakan ini kapan saja. Kami menyarankan Anda untuk memeriksa halaman ini secara berkala.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5 text-muted">
                    <p>Punya pertanyaan? <a href="/kontak-kami" class="text-decoration-none fw-semibold">Hubungi Tim Kami</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .leading-relaxed { line-height: 1.8; }
    .bg-primary-subtle { background-color: #e7f1ff; } /* Sesuai Bootstrap 5.3+ */
</style>
@endsection