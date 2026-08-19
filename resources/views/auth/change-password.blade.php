@extends('layouts.auth')

@section('title', 'Ganti Password Wajib')

@section('content')
<div class="auth-card mx-auto">
    <div class="auth-header">
        <span class="brand-badge bg-warning text-dark">Keamanan Akun</span>
        <h4 class="fw-bold mb-1">Perbarui Password</h4>
        <p class="mb-0 text-white-50 fs-7">Wajib ganti password default saat login pertama</p>
    </div>

    <div class="p-4">
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show fs-7" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show fs-7" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label fw-semibold text-secondary fs-7">Password saat Ini (Default)</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Masukkan password saat ini" required autofocus>
                @error('current_password')
                    <div class="invalid-feedback fs-7">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary fs-7">Password Baru</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter (huruf besar/kecil & angka)" required>
                @error('password')
                    <div class="invalid-feedback fs-7">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold text-secondary fs-7">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
            </div>

            <button type="submit" class="btn btn-primary btn-primary-custom w-100 text-white">
                Simpan & Lanjutkan
            </button>
        </form>
    </div>

    <div class="bg-light p-3 text-center border-top text-muted fs-8">
        &copy; {{ date('Y') }} Fakultas Ekonomi dan Bisnis — Universitas Kuningan
    </div>
</div>
@endsection
