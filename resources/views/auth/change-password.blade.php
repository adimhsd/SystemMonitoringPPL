@extends('layouts.auth')

@section('title', 'Ubah Password Akun')

@section('content')
<div class="auth-card mx-auto">
    <div class="auth-header">
        <span class="brand-badge bg-warning text-dark">Keamanan Akun</span>
        <h4 class="fw-bold mb-1">Perbarui Password Akun</h4>
        <p class="mb-0 text-white-50 fs-7">
            @if(Auth::user()->must_change_password)
                Wajib ganti password default saat login pertama demi keamanan
            @else
                Perbarui password akun Anda secara berkala
            @endif
        </p>
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

        <form action="{{ route('password.change.update') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label fw-semibold text-secondary fs-7">Password Saat Ini</label>
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

            <button type="submit" class="btn btn-primary btn-primary-custom w-100 text-white fw-semibold mb-2">
                Simpan & Perbarui Password
            </button>

            @if(!Auth::user()->must_change_password)
                <div class="text-center mt-3">
                    <a href="javascript:history.back()" class="text-decoration-none text-secondary fs-7">
                        &larr; Batal / Kembali
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="bg-light p-3 text-center border-top text-muted fs-8">
        &copy; {{ date('Y') }} FEB - Universitas Kuningan
    </div>
</div>
@endsection
