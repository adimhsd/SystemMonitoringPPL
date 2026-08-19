@extends('layouts.app')

@section('title', 'Edit Data Mitra')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.mitra.index') }}" class="text-decoration-none text-secondary fs-7">&larr; Kembali ke Master Mitra</a>
    <h4 class="fw-bold mb-1 mt-1">Edit Data Mitra</h4>
</div>

<div class="card card-custom p-4 max-w-2xl mx-auto">
    <form action="{{ route('admin.mitra.update', $mitra) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_mitra" class="form-label fw-semibold text-secondary fs-7">Nama Mitra / Instansi / Perusahaan</label>
            <input type="text" class="form-control @error('nama_mitra') is-invalid @enderror" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required>
            @error('nama_mitra')
                <div class="invalid-feedback fs-7">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label fw-semibold text-secondary fs-7">Kategori Instansi/Mitra</label>
            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                <option value="SKPD" {{ old('kategori', $mitra->kategori) == 'SKPD' ? 'selected' : '' }}>SKPD (Instansi Pemda)</option>
                <option value="Swasta" {{ old('kategori', $mitra->kategori) == 'Swasta' ? 'selected' : '' }}>Swasta / Perusahaan</option>
                <option value="UMKM" {{ old('kategori', $mitra->kategori) == 'UMKM' ? 'selected' : '' }}>UMKM</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="alamat" class="form-label fw-semibold text-secondary fs-7">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $mitra->alamat) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="pic_user_id" class="form-label fw-semibold text-secondary fs-7">Tautkan Akun PIC Mitra (1-to-1)</label>
            <select class="form-select" id="pic_user_id" name="pic_user_id">
                <option value="">-- Belum Ada / Kosongkan --</option>
                @foreach($availablePics as $pic)
                    <option value="{{ $pic->id }}" {{ old('pic_user_id', $mitra->pic_user_id) == $pic->id ? 'selected' : '' }}>
                        {{ $pic->nama_lengkap }} ({{ $pic->username }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-touch text-white rounded-3 w-100 fw-semibold">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
