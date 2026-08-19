@extends('layouts.app')

@section('title', 'Dashboard DPL')

@section('content')
<div class="card card-custom p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Dosen Pembimbing Lapangan</span>
            <h4 class="fw-bold mb-1">{{ $dpl->nama_lengkap }}</h4>
            <p class="text-muted mb-0 fs-7">NIP/NIDN: {{ $dpl->nip_nidn ?? '-' }}</p>
        </div>
        <div class="text-md-end">
            <span class="text-muted fs-7 d-block mb-1">Total Mahasiswa Bimbingan:</span>
            <span class="badge {{ $totalMahasiswa > 10 ? 'bg-danger' : 'bg-success' }} fs-6 px-3 py-2">
                {{ $totalMahasiswa }} / Maks 10 Mahasiswa
            </span>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3">Kelompok Bimbingan PPL</h5>
@if($kelompokBimbingan->isEmpty())
    <div class="card card-custom p-4 text-center text-muted">
        Belum ada kelompok PPL yang diplotkan untuk Anda saat ini.
    </div>
@else
    <div class="row g-3">
        @foreach($kelompokBimbingan as $kelompok)
            <div class="col-12 col-md-6">
                <div class="card card-custom p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold text-primary mb-0">{{ $kelompok->nama_kelompok }}</h6>
                        <span class="badge bg-secondary fs-8">{{ $kelompok->tahun_akademik }}</span>
                    </div>
                    <p class="text-muted fs-7 mb-2">🏢 Mitra: <strong>{{ $kelompok->mitra->nama_mitra }}</strong></p>
                    <p class="text-muted fs-7 mb-2">👤 Ketua: <strong>{{ $kelompok->ketua->nama_lengkap }}</strong></p>
                    <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center">
                        <span class="fs-7 text-secondary">{{ $kelompok->anggota->count() }} Mahasiswa</span>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Logbook</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
