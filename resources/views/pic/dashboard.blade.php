@extends('layouts.app')

@section('title', 'Dashboard PIC Mitra')

@section('content')
<div class="card card-custom p-4 mb-4">
    <span class="badge bg-success px-3 py-2 rounded-pill mb-2 w-auto me-auto">Pembimbing Lapangan Mitra</span>
    <h4 class="fw-bold mb-1">{{ $pic->nama_lengkap }}</h4>
    <p class="text-muted mb-0 fs-7">Instansi/Perusahaan: <strong>{{ $mitra->nama_mitra ?? 'Belum Ditautkan' }}</strong> ({{ $mitra->kategori ?? '-' }})</p>
</div>

<h5 class="fw-bold mb-3">Kelompok Magang PPL yang Dibimbing (1 Kelompok)</h5>
@if(!$kelompok)
    <div class="card card-custom p-4 text-center text-muted">
        Belum ada kelompok PPL yang ditempatkan di instansi Anda.
    </div>
@else
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary mb-0">{{ $kelompok->nama_kelompok }}</h5>
            <span class="badge bg-success">Status: {{ ucfirst($kelompok->status) }}</span>
        </div>
        <p class="text-secondary fs-7 mb-2">👤 Ketua Kelompok: <strong>{{ $kelompok->ketua->nama_lengkap }}</strong> ({{ $kelompok->ketua->no_hp ?? '-' }})</p>
        <p class="text-secondary fs-7 mb-3">👨‍🏫 DPL Fakultas: <strong>{{ $kelompok->dpl->nama_lengkap }}</strong></p>

        <h6 class="fw-semibold text-dark mb-2">Daftar Mahasiswa Magang ({{ $kelompok->anggota->count() }} Orang):</h6>
        <ul class="list-group mb-3">
            @foreach($kelompok->anggota as $mhs)
                <li class="list-group-item d-flex justify-content-between align-items-center fs-7">
                    <span><strong>{{ $mhs->nama }}</strong> ({{ $mhs->nim }})</span>
                    <span class="badge bg-light text-dark border">{{ $mhs->prodi }}</span>
                </li>
            @endforeach
        </ul>

        <div class="d-flex gap-2">
            <a href="#" class="btn btn-primary btn-touch text-white rounded-3 w-100 fw-semibold">
                Lihat & Tandai Logbook Kegiatan
            </a>
        </div>
    </div>
@endif
@endsection
