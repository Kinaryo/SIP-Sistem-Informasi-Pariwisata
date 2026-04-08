@extends('auth.layouts.app-auth')

@section('title', 'Lupa Password')

@section('content')

<div class="col-md-5 col-lg-4">
    <div class="card shadow-lg auth-card">
        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="text-center mb-4">
                <a href="/">
                    <i class="fas fa-unlock-alt text-primary fs-1 mb-2"></i>
                </a>
                <h4 class="fw-bold">Lupa Password</h4>
                <p class="text-muted small">
                    Masukkan email untuk mendapatkan link reset password
                </p>
            </div>

            <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- EMAIL -->
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Masukkan email" required>
                </div>

                @error('email')
                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                @enderror

                <!-- BUTTON -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary py-2">
                        <i class="fas fa-paper-plane"></i> Kirim Link Reset
                    </button>

                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('forgotForm');

    if (form) {
        form.addEventListener('submit', function () {
            Swal.fire({
                title: 'Mengirim link...',
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

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#0d6efd'
});
</script>
@endif

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