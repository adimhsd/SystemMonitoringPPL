@extends('layouts.app')

@section('title', 'Master Data Mahasiswa')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Master Data Mahasiswa</h4>
        <p class="text-muted mb-0 fs-7">Kelola daftar seluruh mahasiswa peserta PPL FEB UNIKU, jenis kelamin, status penempatan kelompok, serta fitur impor/ekspor Excel.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary btn-touch rounded-3 fw-semibold">
            + Tambah Mahasiswa
        </a>
        <a href="{{ route('admin.mahasiswa.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-danger btn-touch rounded-3 fw-semibold">
            📄 Cetak PDF Report
        </a>
        <button type="button" class="btn btn-outline-success btn-touch rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
            📥 Impor Excel
        </button>
        <a href="{{ route('admin.mahasiswa.export', request()->query()) }}" class="btn btn-outline-secondary btn-touch rounded-3 fw-semibold">
            📊 Export Excel
        </a>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card card-custom p-3 mb-4">
    <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama, NIM, Konsentrasi, No HP..." value="{{ request('search') }}">
        </div>
        <div class="col-6 col-md-3">
            <select name="prodi" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Semua Prodi --</option>
                <option value="Manajemen" {{ request('prodi') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                <option value="Akuntansi" {{ request('prodi') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                <option value="Bisnis Digital" {{ request('prodi') == 'Bisnis Digital' ? 'selected' : '' }}>Bisnis Digital</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="jenis_kelamin" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Gender --</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="kelompok_status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Status Plotting --</option>
                <option value="assigned" {{ request('kelompok_status') == 'assigned' ? 'selected' : '' }}>Ada Kelompok</option>
                <option value="unassigned" {{ request('kelompok_status') == 'unassigned' ? 'selected' : '' }}>Belum Diplotkan</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-grid">
            <button type="submit" class="btn btn-sm btn-secondary fw-semibold">Filter Data</button>
        </div>
    </form>
</div>

<!-- Table Data Mahasiswa -->
<div class="card card-custom overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 fs-7">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4">NIM & Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Program Studi</th>
                    <th>Konsentrasi</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Kelompok PPL</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswaList as $mhs)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $mhs->nama }}</div>
                            <div class="text-muted fs-8">NIM: <code>{{ $mhs->nim }}</code></div>
                        </td>
                        <td>
                            @if($mhs->jenis_kelamin === 'Laki-laki')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-2 py-1 fs-8">
                                    👨 Laki-laki
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-1 fs-8">
                                    👩 Perempuan
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-1 fs-8">
                                {{ $mhs->prodi }}
                            </span>
                        </td>
                        <td>{{ $mhs->konsentrasi ?? '-' }}</td>
                        <td>
                            @if($mhs->no_hp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mhs->no_hp) }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                    💬 {{ $mhs->no_hp }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $mhs->alamat }}">
                                {{ $mhs->alamat ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($mhs->kelompok)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20">
                                    🏢 {{ $mhs->kelompok->nama_kelompok }}
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border">Belum Ada Kelompok</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn btn-sm btn-outline-primary">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.mahasiswa.destroy', $mhs) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data mahasiswa yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-3 px-1">
    <div class="text-muted fs-7">
        Menampilkan <strong>{{ $mahasiswaList->firstItem() ?? 0 }}</strong> – <strong>{{ $mahasiswaList->lastItem() ?? 0 }}</strong> dari <strong>{{ $mahasiswaList->total() }}</strong> Mahasiswa
    </div>
    <div>
        {{ $mahasiswaList->links() }}
    </div>
</div>

<!-- Modal Impor Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-custom">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="modalImportExcelLabel">📥 Impor Data Mahasiswa dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body fs-7">
                    <p class="text-muted mb-2">Unggah berkas Excel (<code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code>) yang berisi data mahasiswa.</p>

                    <div class="p-3 bg-light rounded-3 mb-3 border fs-8">
                        <strong class="d-block mb-1 text-dark">📋 Petunjuk Baris Header Kolom Excel:</strong>
                        <ul class="mb-0 ps-3 text-secondary">
                            <li><code>nim</code> : Nomor Induk Mahasiswa (Wajib, Unik)</li>
                            <li><code>nama_mahasiswa</code> / <code>nama</code> : Nama Lengkap Mahasiswa</li>
                            <li><code>jenis_kelamin</code> / <code>jk</code> : Laki-laki / Perempuan (L / P)</li>
                            <li><code>program_studi</code> / <code>prodi</code> : Manajemen / Akuntansi / Bisnis Digital</li>
                            <li><code>konsentrasi</code> / <code>kelas</code> : Konsentrasi / Peminatan (opsional)</li>
                            <li><code>no_hp_whatsapp</code> / <code>no_hp</code> : Nomor Telepon/WA (opsional)</li>
                            <li><code>alamat</code> : Alamat Rumah (opsional)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-semibold text-secondary">Pilih Berkas Excel:</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold">Unggah & Impor Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
