@extends('layouts.app')

@section('title', 'Detail Logbook — PIC Mitra')

@section('content')
<div class="mb-4">
    <a href="{{ route('pic.logbook.index') }}" class="text-decoration-none text-secondary fs-7">&larr; Kembali ke Daftar Logbook</a>
    <h4 class="fw-bold mb-1 mt-1">Detail Logbook Harian PPL</h4>
</div>

<div class="row g-3">
    <div class="col-12 col-md-8">
        <div class="card card-custom p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-primary mb-0">{{ $logbook->tanggal->translatedFormat('l, d F Y') }}</h5>
                <span class="badge bg-secondary fs-7">
                    🕒 {{ \Carbon\Carbon::parse($logbook->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($logbook->waktu_selesai)->format('H:i') }} WIB
                </span>
            </div>

            <h6 class="fw-bold text-dark mb-2">Uraian & Deskripsi Kegiatan:</h6>
            <p class="text-secondary fs-7 mb-4 style-pre-line" style="white-space: pre-line;">{{ $logbook->deskripsi_kegiatan }}</p>

            <h6 class="fw-bold text-dark mb-2">Foto Dokumentasi:</h6>
            <div class="text-center p-3 bg-light rounded-3 mb-3 border">
                <img src="{{ route('logbook.foto', $logbook) }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 400px; object-fit: contain;" alt="Foto Dokumentasi">
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card card-custom p-4 mb-3">
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Informasi & Approval PIC Mitra</h6>

            <p class="text-secondary fs-7 mb-2">👥 <strong>Kelompok:</strong> {{ $logbook->kelompok->nama_kelompok }}</p>
            <p class="text-secondary fs-7 mb-2">👨‍🏫 <strong>DPL Fakultas:</strong> {{ $logbook->kelompok->dpl->nama_lengkap ?? '-' }}</p>
            <p class="text-secondary fs-7 mb-3">👑 <strong>Ketua:</strong> {{ $logbook->kelompok->ketua->nama_lengkap ?? '-' }}</p>

            <hr>

            <h6 class="fw-bold text-dark fs-7 mb-2">Status Approval PIC Mitra:</h6>
            @if($logbook->dilihat_mitra)
                <div class="alert alert-success fs-7 mb-3">
                    ✓ Telah di-approve pada {{ $logbook->dilihat_mitra_at->translatedFormat('d F Y H:i') }} WIB
                </div>
            @else
                <div class="alert alert-warning fs-7 mb-3">
                    ⚠️ Belum di-approve oleh Anda.
                </div>
                <form action="{{ route('pic.logbook.viewed', $logbook) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-touch text-white w-100 fw-semibold rounded-3">
                        ✓ Approve Logbook
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
