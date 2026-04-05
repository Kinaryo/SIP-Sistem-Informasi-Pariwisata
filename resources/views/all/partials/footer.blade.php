<footer id="kontak" class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">

            <div class="col-md-4">
                <h4 class="fw-bold">
                    <i class="fas fa-map-marked-alt text-primary"></i>
                    Loka<span class="text-primary">TRIP</span>
                </h4>
                <p class="text-secondary small">
                    Sistem informasi pariwisata Indonesia yang membantu wisatawan
                    menemukan destinasi terbaik diwilayah timur nusantara.
                </p>
            </div>

            <div class="col-md-2">
                <h6 class="fw-bold">Tautan</h6>
                <ul class="list-unstyled small">
                    <li><a href="/tentang-kami" class="text-secondary text-decoration-none">Tentang Kami</a></li>
                    <li><a href="/kontak-kami" class="text-secondary text-decoration-none">Kontak Kami</a></li>
                    
                    <!-- ✅ TAMBAHAN WAJIB ADSENSE -->
                    <li><a href="{{ route('privacy') }}" class="text-secondary text-decoration-none">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-secondary text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Kontak</h6>
                <p class="text-secondary small mb-1">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                    Papua Selatan, Indonesia
                </p>
                <p class="text-secondary small mb-1">
                    <i class="fas fa-phone text-primary"></i>
                    +62 821 9905 7253
                </p>
                <p class="text-secondary small">
                    <i class="fas fa-envelope text-primary"></i>
                    jelajahimyid@gmail.com
                </p>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Media Sosial</h6>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

        </div>

        <hr class="border-secondary my-4">

        <!-- BAGIAN BAWAH (LEGAL LINK) -->
        <div class="text-center small text-secondary">
            © {{ date('Y') }} LokaTRIP - Sistem Informasi Pariwisata <br>

            <a href="{{ route('privacy') }}" class="text-secondary text-decoration-none">Privacy Policy</a> |
            <a href="{{ route('terms') }}" class="text-secondary text-decoration-none">Terms & Conditions</a>
        </div>
    </div>
</footer>