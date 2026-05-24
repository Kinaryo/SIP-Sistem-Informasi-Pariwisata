<footer id="kontak" class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">

            <div class="col-md-3">
                <h4 class="fw-bold">
                    <i class="fas fa-map-marked-alt text-primary"></i>
                    Visit<span class="text-primary">MERAUKE</span>
                </h4>

                <p class="text-secondary small" style="text-align: justify">
                    VisitMerauke.com adalah platform informasi digital yang menyajikan berbagai informasi
                    tentang Merauke, mulai dari destinasi wisata, budaya, produk lokal dalam satu tempat.
                </p>
            </div>

            <div class="col-md-2">
                <h6 class="fw-bold">Tautan</h6>

                <ul class="list-unstyled small mt-3">
                    <li><a href="/tentang-kami" class="text-secondary text-decoration-none">Tentang Kami</a></li>
                    <li><a href="/kontak-kami" class="text-secondary text-decoration-none">Kontak Kami</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-secondary text-decoration-none">Privacy Policy</a>
                    </li>
                    <li><a href="{{ route('terms') }}" class="text-secondary text-decoration-none">Terms &
                            Conditions</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Kontak</h6>

                <div class="mt-3">
                    <p class="text-secondary small mb-1">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        Merauke, Papua Selatan, Indonesia
                    </p>

                    <p class="text-secondary small mb-1">
                        <i class="fas fa-phone text-primary"></i>
                        +62 821 9905 7253
                    </p>

                    <p class="text-secondary small">
                        <i class="fas fa-envelope text-primary"></i>
                        meraukevisit@gmail.com
                    </p>
                </div>
            </div>

            <!-- VISITOR (AJAX READY) -->
            <div class="col-md-2">
                <h6 class="fw-bold">Visitors</h6>

                <div class="mt-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-secondary">Hari Ini</small>
                        <strong class="text-white" id="visitors_today">0</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-secondary">Bulan Ini</small>
                        <strong class="text-white" id="visitors_month">0</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-secondary">Total Visitor</small>
                        <strong class="text-warning" id="visitors_total">0</strong>
                    </div>

                </div>
            </div>

            <div class="col-md-2">
                <h6 class="fw-bold">Media Sosial</h6>

                <div class="mt-3">
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

        </div>

        <hr class="border-secondary my-4">

        <div class="text-center small text-secondary">
            © {{ date('Y') }} visitmerauke.com <br>

            <a href="#" class="text-secondary text-decoration-none">
                V.{{ config('app.version') }}
            </a> |

            <a href="{{ route('privacy') }}" class="text-secondary text-decoration-none">
                Privacy Policy
            </a> |

            <a href="{{ route('terms') }}" class="text-secondary text-decoration-none">
                Terms & Conditions
            </a>
        </div>
    </div>
</footer>


<script>
    document.addEventListener("DOMContentLoaded", function () {

        fetch("{{ route('footer.stats') }}")
            .then(response => response.json())
            .then(data => {

                document.getElementById('visitors_today').innerText =
                    new Intl.NumberFormat().format(data.visitors_today);

                document.getElementById('visitors_month').innerText =
                    new Intl.NumberFormat().format(data.visitors_month);

                document.getElementById('visitors_total').innerText =
                    new Intl.NumberFormat().format(data.visitors);

            })
            .catch(error => {
                console.error("Footer stats error:", error);
            });

    });
</script>