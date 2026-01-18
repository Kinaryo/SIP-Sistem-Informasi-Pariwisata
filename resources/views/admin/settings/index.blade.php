@extends('admin.layouts.app-admin')

@section('title', 'Settings Admin')
@section('page-title', 'Settings')

@section('content')
<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="mb-4">Pengaturan Kantor</h5>
            <form id="settingForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Office Name</label>
                    <input type="text" name="office_name" value="{{ $setting->office_name }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" value="{{ $setting->longitude }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" value="{{ $setting->latitude }}" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded">Update Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('settingForm').addEventListener('submit', function(e){
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    
    // Konversi FormData ke Object JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // Tampilkan Loading
    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang memperbarui data...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch("{{ route('admin.settings.update', $setting->id) }}", {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const json = await res.json();
        if (res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: json.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            // Jika error validasi (422), gabungkan semua pesan errornya
            let errorMsg = json.message;
            if (json.errors) {
                errorMsg = Object.values(json.errors).flat().join('<br>');
            }
            throw new Error(errorMsg);
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            html: err.message || 'Terjadi kesalahan sistem',
        });
    });
});
</script>
@endpush