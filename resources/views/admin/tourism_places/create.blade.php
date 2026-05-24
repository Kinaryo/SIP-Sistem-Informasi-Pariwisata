@extends('admin.layouts.app-admin')

@section('title', 'Tambah Wisata')
@section('page-title', 'Tambah Destinasi Wisata')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">

        <form id="formWisata" action="{{ route('admin.tourism-places.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                {{-- NAME --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Wisata <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- LOCATION --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                    <input type="text" name="province" class="form-control" value="{{ old('province') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kota / Kabupaten <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kecamatan / Distrik</label>
                    <input type="text" name="district" class="form-control" value="{{ old('district') }}">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>

                {{-- MAP --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Pilih Lokasi di Peta</label>
                    <div id="map" style="height: 400px;"></div>
                </div>

                {{-- LAT LONG --}}
                <div class="col-md-6">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="form-control mt-2" placeholder="Latitude">
                </div>
                <div class="col-md-6">    
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="form-control mt-2" placeholder="Longitude">
                </div>

                {{-- DESCRIPTION --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                </div>

                {{-- PRICE --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Tiket <span class="text-danger">*</span></label>
                    <input type="number" name="ticket_price" class="form-control" value="{{ old('ticket_price', 0) }}" min="0" required>
                </div>

                {{-- OPEN TIME --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Buka <span class="text-danger">*</span></label>
                    <input type="time" name="open_time" class="form-control" value="{{ old('open_time') }}" required>
                </div>

                {{-- CLOSE TIME --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Tutup <span class="text-danger">*</span></label>
                    <input type="time" name="close_time" class="form-control" value="{{ old('close_time') }}" required>
                </div>

                {{-- CONTACT --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kontak</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact') }}">
                </div>

                {{-- COVER IMAGE --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                </div>

                {{-- FACILITIES --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Fasilitas</label>
                    <div class="row">
                        @foreach ($facilities as $facility)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                        {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $facility->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- GALLERY --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Galeri Wisata</label>
                    @for($i = 0; $i < 10; $i++)
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <input type="file" name="gallery[{{ $i }}][image]" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="gallery[{{ $i }}][title]" class="form-control" placeholder="Judul">
                            </div>
                        </div>
                    @endfor
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.tourism-places.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Wisata</button>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- LEAFLET -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
// ================= SWEET ALERT =================

// VALIDATION ERROR
@if ($errors->any())
Swal.fire({
    icon: 'error',
    title: 'Validasi Gagal!',
    html: `{!! implode('<br>', $errors->all()) !!}`
});
@endif

// SUCCESS
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    timer: 2500,
    showConfirmButton: false
});
@endif

// ERROR
@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}"
});
@endif

// CONFIRM SUBMIT + LOADING DETAIL
document.getElementById('formWisata').addEventListener('submit', function(e){
    e.preventDefault();

    Swal.fire({
        title: 'Simpan Data Wisata?',
        text: "Pastikan semua data sudah benar sebelum disimpan.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {

            Swal.fire({
                title: 'Sedang Menyimpan Data...',
                html: `
                    <div style="font-size:14px">
                      Mohon tunggu sebentar...
                    </div>
                `,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                e.target.submit();
            }, 800);
        }
    });
});


// ================= MAP =================
const map = L.map('map').setView([-2.548926, 118.0148634], 5);

const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
const googleStreet = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'] });
const googleSat = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'] });

osm.addTo(map);

L.control.layers({
    "OpenStreetMap": osm,
    "Google Street": googleStreet,
    "Google Satellite": googleSat
}).addTo(map);

let marker;

function setMarker(lat, lng) {
    if(marker) map.removeLayer(marker);

    marker = L.marker([lat, lng], { draggable:true }).addTo(map);

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    marker.on('dragend', function() {
        let pos = marker.getLatLng();
        latitude.value = pos.lat;
        longitude.value = pos.lng;
    });
}

map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
});

L.Control.geocoder({ defaultMarkGeocode:false })
.on('markgeocode', function(e) {
    let latlng = e.geocode.center;
    map.setView(latlng, 14);
    setMarker(latlng.lat, latlng.lng);
}).addTo(map);

// INPUT MANUAL
latitude.addEventListener('change', updateFromInput);
longitude.addEventListener('change', updateFromInput);

function updateFromInput() {
    let lat = parseFloat(latitude.value);
    let lng = parseFloat(longitude.value);

    if(!isNaN(lat) && !isNaN(lng)) {
        setMarker(lat, lng);
        map.setView([lat, lng], 14);
    }
}

// OLD VALUE
@if(old('latitude') && old('longitude'))
    setMarker({{ old('latitude') }}, {{ old('longitude') }});
    map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 12);
@endif

</script>

@endpush