@extends('layouts.app')

@section('title', 'Tambah Mitra Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.mitra.index') }}" class="text-decoration-none text-secondary fs-7">&larr; Kembali ke Master Mitra</a>
    <h4 class="fw-bold mb-1 mt-1">Tambah Mitra PPL Baru</h4>
</div>

<div class="card card-custom p-4 max-w-2xl mx-auto" x-data="{ picOption: 'existing' }">
    <form action="{{ route('admin.mitra.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label for="nama_mitra" class="form-label fw-semibold text-secondary fs-7">Nama Mitra / Instansi / Perusahaan</label>
            <input type="text" class="form-control @error('nama_mitra') is-invalid @enderror" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}" placeholder="Contoh: BAPPEDA Kuningan" required>
            @error('nama_mitra')
                <div class="invalid-feedback fs-7">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label fw-semibold text-secondary fs-7">Kategori Instansi/Mitra</label>
            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="SKPD" {{ old('kategori') == 'SKPD' ? 'selected' : '' }}>SKPD (Instansi Pemda)</option>
                <option value="Swasta" {{ old('kategori') == 'Swasta' ? 'selected' : '' }}>Swasta / Perusahaan</option>
                <option value="UMKM" {{ old('kategori') == 'UMKM' ? 'selected' : '' }}>UMKM</option>
            </select>
            @error('kategori')
                <div class="invalid-feedback fs-7">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="alamat" class="form-label fw-semibold text-secondary fs-7">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap mitra">{{ old('alamat') }}</textarea>
        </div>

        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Penautan Akun PIC Mitra (1 PIC = 1 Mitra)</h6>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pic_option" id="pic_existing" value="existing" x-model="picOption">
                <label class="form-check-label fs-7" for="pic_existing">Pilih dari Akun PIC yang Ada</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pic_option" id="pic_new" value="new" x-model="picOption">
                <label class="form-check-label fs-7" for="pic_new">+ Buatkan Akun PIC Baru</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="pic_option" id="pic_none" value="none" x-model="picOption">
                <label class="form-check-label fs-7 text-muted" for="pic_none">Tautkan Nanti</label>
            </div>
        </div>

        <!-- Option Existing PIC -->
        <div class="mb-4" x-show="picOption === 'existing'">
            <label for="pic_user_id" class="form-label fw-semibold text-secondary fs-7">Pilih Akun PIC Mitra</label>
            <select class="form-select @error('pic_user_id') is-invalid @enderror" id="pic_user_id" name="pic_user_id">
                <option value="">-- Pilih PIC Mitra --</option>
                @foreach($availablePics as $pic)
                    <option value="{{ $pic->id }}" {{ old('pic_user_id') == $pic->id ? 'selected' : '' }}>
                        {{ $pic->nama_lengkap }} ({{ $pic->username }})
                    </option>
                @endforeach
            </select>
            <div class="form-text fs-8">Hanya menampilkan akun PIC Mitra yang belum ditautkan ke mitra lain.</div>
        </div>

        <!-- Option New PIC -->
        <div class="p-3 bg-light rounded-3 mb-4 border" x-show="picOption === 'new'">
            <h6 class="fw-bold fs-7 mb-3 text-primary">Data Akun PIC Mitra Baru</h6>
            <div class="mb-2">
                <label class="form-label fs-7 fw-semibold">Nama Lengkap PIC</label>
                <input type="text" name="new_pic_nama" class="form-control fs-7" placeholder="Nama Pembimbing Mitra" value="{{ old('new_pic_nama') }}">
            </div>
            <div class="mb-2">
                <label class="form-label fs-7 fw-semibold">Username Login</label>
                <input type="text" name="new_pic_username" class="form-control fs-7" placeholder="Username unik" value="{{ old('new_pic_username') }}">
            </div>
            <div class="mb-2">
                <label class="form-label fs-7 fw-semibold">No HP / Whatsapp</label>
                <input type="text" name="new_pic_hp" class="form-control fs-7" placeholder="0812xxxx" value="{{ old('new_pic_hp') }}">
            </div>
            <div class="form-text fs-8 text-muted">Password default otomatis diset ke <code>password</code> dan wajib diganti saat login pertama.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-touch text-white rounded-3 w-100 fw-semibold">
            Simpan Master Mitra
        </button>
    </form>
</div>
@endsection
