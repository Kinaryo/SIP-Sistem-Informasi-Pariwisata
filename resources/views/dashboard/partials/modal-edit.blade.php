{{-- MODAL EDIT HERO --}}
<div class="modal fade" id="editHeroModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('dashboard.updateHero', $tourism_place->id) }}"
            enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Hero Wisata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Wisata</label>
                    <input type="text" name="name" value="{{ $tourism_place->name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                            <option value="{{ $cat->id }}" @selected($tourism_place->category_id == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DESKRIPSI --}}
<div class="modal fade" id="editDescriptionModal">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('dashboard.updateDescription', $tourism_place->id) }}"
            class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5>Edit Deskripsi</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea name="description" class="form-control" rows="6">{{ $tourism_place->description }}</textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT FASILITAS --}}
<div class="modal fade" id="editFacilitiesModal">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('dashboard.updateFacilities', $tourism_place->id) }}"
            class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5>Edit Fasilitas</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach(\App\Models\Facility::orderBy('name')->get() as $facility)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="facilities[]"
                                    value="{{ $facility->id }}"
                                    @checked($tourism_place->facilities->contains($facility->id))>
                                <label class="form-check-label">{{ $facility->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH GALERI --}}
<div class="modal fade" id="addGalleryModal">
    <div class="modal-dialog">
        <form id="addGalleryForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5>Tambah Foto</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="image" class="form-control mb-2" required>
                <input type="text" name="title" class="form-control" placeholder="Judul Foto" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT GALERI --}}
<div class="modal fade" id="editGalleryModal">
    <div class="modal-dialog">
        <form id="editGalleryForm" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5>Edit Foto</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="image" class="form-control mb-2">
                <input type="text" name="title" id="editTitle" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT INFO WISATA --}}
<div class="modal fade" id="editInfoModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('dashboard.updateInfo', $tourism_place->id) }}" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Info Wisata</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Harga Tiket</label>
                    <input type="number" name="ticket_price" value="{{ $tourism_place->ticket_price }}"
                        class="form-control">
                </div>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Jam Buka</label>
                        <input type="time" name="open_time" value="{{ $tourism_place->open_time }}"
                            class="form-control">
                    </div>
                    <div class="col">
                        <label class="form-label">Jam Tutup</label>
                        <input type="time" name="close_time" value="{{ $tourism_place->close_time }}"
                            class="form-control">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Kontak</label>
                    <input type="text" name="contact" value="{{ $tourism_place->contact }}" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Lokasi--}}

<div class="modal fade" id="editLocationModal">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('dashboard.updateLocation', $tourism_place->id) }}" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5>Edit Lokasi Wisata</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label>Provinsi</label>
                        <input type="text" name="province" value="{{ $tourism_place->location->province }}"
                            class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Kabupaten / Kota</label>
                        <input type="text" name="city" value="{{ $tourism_place->location->city }}"
                            class="form-control">
                    </div>
                </div>

                <div class="mt-2">
                    <label>Alamat</label>
                    <textarea name="address" class="form-control">{{ $tourism_place->location->address }}</textarea>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label>Latitude</label>
                        <input type="text" id="latInput" name="latitude"
                            value="{{ $tourism_place->location->latitude }}" class="form-control">
                    </div>
                    <div class="col">
                        <label>Longitude</label>
                        <input type="text" id="lngInput" name="longitude"
                            value="{{ $tourism_place->location->longitude }}" class="form-control">
                    </div>
                </div>

                <div id="editMap" style="height:300px" class="mt-3 rounded"></div>
                <small class="text-muted">Geser marker untuk mengubah lokasi</small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Simpan Lokasi</button>
            </div>
        </form>
    </div>
</div>