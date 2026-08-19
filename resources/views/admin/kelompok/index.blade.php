@extends('layouts.app')

@section('title', 'Data Kelompok PPL')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Data Master & Akun Kelompok PPL</h4>
        <p class="text-muted mb-0 fs-7">Kelola akun kelompok mandiri (kredensial login) yang digunakan oleh seluruh mahasiswa anggota kelompok PPL.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary btn-touch rounded-3 fw-semibold">
            + Buat Akun Kelompok
        </a>
        <a href="{{ route('admin.plotting.index') }}" class="btn btn-outline-success btn-touch rounded-3 fw-semibold">
            🗺️ Plotting Kelompok
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-custom p-3 mb-4">
    <form action="{{ route('admin.kelompok.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama Kelompok / Username Login..." value="{{ request('search') }}">
        </div>
        <div class="col-6 col-md-3">
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Semua Tahun --</option>
                <option value="2026/2027" {{ request('tahun') == '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                <option value="2025/2026" {{ request('tahun') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Status --</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-grid">
            <button type="submit" class="btn btn-sm btn-secondary fw-semibold">Filter Data</button>
        </div>
    </form>
</div>

<!-- Table Data Kelompok -->
<div class="card card-custom overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 fs-7">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4">Nama Kelompok</th>
                    <th>Username Akun</th>
                    <th>Mitra Penempatan</th>
                    <th>DPL Pembimbing</th>
                    <th>Jumlah Anggota</th>
                    <th>Status Plotting</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompokList as $k)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">👥 {{ $k->nama_kelompok }}</div>
                            <div class="text-muted fs-8">Tahun: {{ $k->tahun_akademik }}</div>
                        </td>
                        <td>
                            <code>{{ $k->ketua->username ?? '-' }}</code>
                        </td>
                        <td>
                            @if($k->mitra)
                                <div class="fw-semibold text-dark">{{ $k->mitra->nama_mitra }}</div>
                            @else
                                <span class="badge bg-light text-muted border">Belum Diplotkan</span>
                            @endif
                        </td>
                        <td>
                            @if($k->dpl)
                                <div class="fw-semibold text-dark">{{ $k->dpl->nama_lengkap }}</div>
                            @else
                                <span class="badge bg-light text-muted border">Belum Diplotkan</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary px-3 py-1">{{ $k->anggota->count() }} Mahasiswa</span>
                        </td>
                        <td>
                            @if($k->mitra && $k->dpl && $k->anggota->count() > 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20">Lengkap</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.kelompok.show', $k) }}" class="btn btn-sm btn-outline-info">
                                    👁️ Detail
                                </a>
                                <a href="{{ route('admin.kelompok.edit', $k) }}" class="btn btn-sm btn-outline-primary">
                                    🔑 Akun & Kredensial
                                </a>
                                <a href="{{ route('admin.plotting.edit', $k) }}" class="btn btn-sm btn-outline-success">
                                    🗺️ Plotting
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada kelompok PPL yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-3 px-1">
    <div class="text-muted fs-7">
        Menampilkan <strong>{{ $kelompokList->firstItem() ?? 0 }}</strong> – <strong>{{ $kelompokList->lastItem() ?? 0 }}</strong> dari <strong>{{ $kelompokList->total() }}</strong> Kelompok
    </div>
    <div>
        {{ $kelompokList->links() }}
    </div>
</div>
@endsection
