@extends('auth.layouts.app-auth')

@section('title', 'Reset Password')

@section('content')

<div class="col-md-5 col-lg-4">
    <div class="card shadow-lg auth-card">
        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="text-center mb-4">
                <a href="/">
                    <i class="fas fa-key text-primary fs-1 mb-2"></i>
                </a>
                <h4 class="fw-bold">Reset Password</h4>
                <p class="text-muted small">Masukkan password baru untuk akun Anda</p>
            </div>

            <form id="resetForm" method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required>
                </div>

                @error('password')
                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                @enderror

                <!-- KONFIRMASI -->
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="form-control" required>
                </div>

                <!-- BUTTON -->
                <button class="btn btn-primary w-100 py-2">
                    <i class="fas fa-sync-alt"></i> Reset Password
                </button>
            </form>

            <hr>

            <!-- BACK TO LOGIN -->
            <div class="text-center small">
                <a href="{{ route('login') }}">Kembali Kelogin</a>
            </div>

        </div>
    </div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('resetForm');

    if (form) {
        form.addEventListener('submit', function () {
            Swal.fire({
                title: 'Memproses...',
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
    confirmButtonColor: '#198754'
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