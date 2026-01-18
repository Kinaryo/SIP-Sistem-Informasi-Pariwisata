@extends('admin.layouts.app-admin')

@section('title', 'Tambah Wisata')
@section('page-title', 'Tambah Destinasi Wisata')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">

        <form action="{{ route('admin.tourism-places.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                {{-- NAME --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Wisata</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori</label>
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
                    <label class="form-label fw-semibold">Provinsi</label>
                    <input type="text" name="province" class="form-control" value="{{ old('province') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kota / Kabupaten</label>
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

                {{-- MAP PICKER --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Pilih Lokasi di Peta</label>
                    <div id="map" style="height: 400px;">
                                   
                </div>
                </div>
                 <div class="col-md-6">
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="form-control mt-2" placeholder="Latitude" readonly>
                                    </div>
                                <div class="col-md-6">    
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="form-control mt-2" placeholder="Longitude" readonly>
                </div>
                {{-- DESCRIPTION --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                </div>

                {{-- PRICE --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Tiket</label>
                    <input type="number" name="ticket_price" class="form-control" value="{{ old('ticket_price', 0) }}" min="0" required>
                </div>

                {{-- OPEN TIME --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Buka</label>
                    <input type="time" name="open_time" class="form-control" value="{{ old('open_time') }}" required>
                </div>

                {{-- CLOSE TIME --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Tutup</label>
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
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="facilities[]"
                                        value="{{ $facility->id }}"
                                        id="facility_{{ $facility->id }}"
                                        {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="facility_{{ $facility->id }}">
                                        {{ $facility->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                {{-- GALLERY --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Galeri Wisata (maks 10 gambar)</label>
                    @for($i = 0; $i < 10; $i++)
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <input type="file" name="gallery[{{ $i }}][image]" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="gallery[{{ $i }}][title]" class="form-control" placeholder="Judul Gambar" value="{{ old('gallery.' . $i . '.title') }}">
                            </div>
                        </div>
                    @endfor
                </div>

            </div>

            {{-- ACTION --}}
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.tourism-places.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Wisata</button>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([-2.548926, 118.0148634], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; OpenStreetMap contributors',
}).addTo(map);

let marker;
function setMarker(lat, lng) {
    if(marker) map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
});

// jika ada old value
@if(old('latitude') && old('longitude'))
    setMarker({{ old('latitude') }}, {{ old('longitude') }});
    map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 12);
@endif
</script>
@endpush
