@extends('layouts.app')

@section('title', 'Dashboard Ketua Kelompok')

@section('content')
<div class="card card-custom p-4 mb-4">
    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2 w-auto me-auto">Ketua Kelompok PPL</span>
    <h4 class="fw-bold mb-1">Selamat Datang, {{ $ketua->nama_lengkap }}</h4>
    <p class="text-muted mb-0 fs-7">Gunakan aplikasi ini untuk menginput logbook harian & mengunggah luaran akhir PPL.</p>
</div>

@if(!$kelompok)
    <div class="card card-custom p-4 text-center text-muted">
        Anda belum terdaftar dalam kelompok PPL aktif. Silakan hubungi Admin Fakultas.
    </div>
@else
    <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold text-primary mb-3">{{ $kelompok->nama_kelompok }}</h5>
        <div class="row g-3 fs-7 text-secondary mb-3">
            <div class="col-12 col-sm-6">
                🏢 <strong>Lokasi Mitra:</strong> {{ $kelompok->mitra->nama_mitra }} ({{ $kelompok->mitra->kategori }})
            </div>
            <div class="col-12 col-sm-6">
                👨‍🏫 <strong>DPL Fakultas:</strong> {{ $kelompok->dpl->nama_lengkap }}
            </div>
        </div>

        <div class="d-grid gap-2 d-sm-flex">
            <a href="#" class="btn btn-primary btn-touch text-white rounded-3 fw-semibold">
                + Input Logbook Hari Ini
            </a>
            <a href="#" class="btn btn-outline-secondary btn-touch rounded-3 fw-semibold">
                Upload Luaran Akhir
            </a>
        </div>
    </div>
@endif
@endsection
