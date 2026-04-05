@extends('all.layouts.app-all')

@section('title', 'Syarat & Ketentuan')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                
                <div class="text-center mb-5">
                    <h1 class="fw-bold display-6 text-dark">Syarat & Ketentuan</h1>
                    <p class="text-muted mx-auto" style="max-width: 600px;">
                        Harap baca dokumen ini dengan seksama. Dengan menggunakan layanan kami, Anda dianggap memahami dan menyetujui seluruh poin di bawah ini.
                    </p>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="progress rounded-0" style="height: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="d-flex mb-5">
                            <div class="flex-shrink-0 me-4 d-none d-md-block">
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark">1. Penggunaan Website</h5>
                                <p class="text-secondary">Website ini didedikasikan sebagai platform informasi pariwisata dan promosi produk lokal. Pengguna diwajibkan:</p>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="text-success me-2">✓</i> Menggunakan informasi secara bijak dan legal.</li>
                                    <li><i class="text-danger me-2">✗</i> Dilarang keras melakukan aktivitas ilegal atau merusak sistem kami.</li>
                                </ul>
                            </div>
                        </div>

                        <hr class="opacity-25 my-4">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3">
                                    <h6 class="fw-bold text-primary mb-3 text-uppercase small">2. Konten & Informasi</h6>
                                    <p class="small text-muted">Semua konten bersifat informatif dan dapat berubah sewaktu-waktu tanpa pemberitahuan terlebih dahulu untuk akurasi data.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3">
                                    <h6 class="fw-bold text-primary mb-3 text-uppercase small">3. Tanggung Jawab</h6>
                                    <p class="small text-muted">Kami tidak bertanggung jawab atas segala kerugian (langsung maupun tidak langsung) yang timbul akibat penggunaan data dari website ini.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-3">
                                    <h6 class="fw-bold mb-2">4. Produk & Transaksi</h6>
                                    <p class="small mb-0">Website ini hanya sebagai media penghubung. Transaksi antara pembeli dan penjual sepenuhnya merupakan tanggung jawab pihak yang bersangkutan.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-3">
                                    <h6 class="fw-bold mb-2">5. Tautan Eksternal</h6>
                                    <p class="small mb-0">Tautan ke pihak ketiga (seperti WhatsApp penjual) berada di luar kendali kami. Kami tidak bertanggung jawab atas kebijakan privasi mereka.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-4 border rounded-4 bg-primary bg-opacity-10 border-primary border-opacity-25 text-center">
                            <h5 class="fw-bold mb-3 text-dark">Persetujuan & Perubahan</h5>
                            <p class="text-secondary mb-0 small">
                                Kami berhak mengubah layanan atau syarat ini kapan saja. Dengan melanjutkan penggunaan website, Anda dianggap menyetujui versi terbaru dari dokumen ini.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    /* Custom Styling tambahan */
    .card { transition: transform 0.3s ease; }
    ul li i { font-weight: bold; }
    .text-secondary { line-height: 1.7; }
</style>
@endsection