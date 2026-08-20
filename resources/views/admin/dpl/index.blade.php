@extends('layouts.app')

@section('title', 'Master Data DPL (Dosen Pembimbing)')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Master Data Dosen Pembimbing Lapangan (DPL)</h4>
        <p class="text-muted mb-0 fs-7">Kelola akun dan data DPL Fakultas, pemantauan beban mahasiswa bimbingan, serta fitur ekspor/impor Excel.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.dpl.create') }}" class="btn btn-primary btn-touch rounded-3 fw-semibold">
            + Tambah DPL Baru
        </a>
        <a href="{{ route('admin.dpl.template') }}" class="btn btn-outline-info btn-touch rounded-3 fw-semibold">
            📄 Download Template Excel
        </a>
        <button type="button" class="btn btn-outline-success btn-touch rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalImportExcelDpl">
            📥 Impor Excel
        </button>
        <a href="{{ route('admin.dpl.export') }}" class="btn btn-outline-secondary btn-touch rounded-3 fw-semibold">
            📊 Export Excel
        </a>
    </div>
</div>

<!-- Search Card -->
<div class="card card-custom p-3 mb-4">
    <form action="{{ route('admin.dpl.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-8">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama DPL, NIP/NIDN, Username, No HP..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-md-4 d-grid">
            <button type="submit" class="btn btn-sm btn-secondary fw-semibold">Cari Data DPL</button>
        </div>
    </form>
</div>

<!-- Table Data DPL -->
<div class="card card-custom overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 fs-7">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4">Nama DPL & NIP/NIDN</th>
                    <th>Username Login</th>
                    <th>Kontak HP & Email</th>
                    <th>Beban Mahasiswa Bimbingan</th>
                    <th>Status Akun</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dplList as $dpl)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">👨‍🏫 {{ $dpl->nama_lengkap }}</div>
                            <div class="text-muted fs-8">NIP/NIDN: {{ $dpl->nip_nidn ?? '-' }}</div>
                        </td>
                        <td><code>{{ $dpl->username }}</code></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $dpl->no_hp ?? '-' }}</div>
                            <div class="text-muted fs-8">{{ $dpl->email ?? '-' }}</div>
                        </td>
                        <td>
                            @if($dpl->total_bimbingan_mhs > 10)
                                <span class="badge bg-danger px-3 py-1">⚠️ {{ $dpl->total_bimbingan_mhs }} / 10 Mahasiswa</span>
                            @elseif($dpl->total_bimbingan_mhs > 0)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-3 py-1">
                                    👨‍🎓 {{ $dpl->total_bimbingan_mhs }} / 10 Mahasiswa
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">Belum Ada Bimbingan</span>
                            @endif
                        </td>
                        <td>
                            @if($dpl->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.dpl.edit', $dpl) }}" class="btn btn-sm btn-outline-primary">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.dpl.destroy', $dpl) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data DPL ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data DPL yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-3 px-1">
    <div class="text-muted fs-7">
        Menampilkan <strong>{{ $dplList->firstItem() ?? 0 }}</strong> – <strong>{{ $dplList->lastItem() ?? 0 }}</strong> dari <strong>{{ $dplList->total() }}</strong> DPL
    </div>
    <div>
        {{ $dplList->links() }}
    </div>
</div>

<!-- Modal Impor Excel DPL -->
<div class="modal fade" id="modalImportExcelDpl" tabindex="-1" aria-labelledby="modalImportExcelDplLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-custom">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="modalImportExcelDplLabel">📥 Impor Data DPL dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.dpl.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body fs-7">
                    <p class="text-muted mb-2">Unggah berkas Excel (<code>.xlsx</code>, <code>.xls</code>, atau <code>.csv</code>) sesuai dengan format template resmi DPL.</p>

                    <div class="mb-3">
                        <a href="{{ route('admin.dpl.template') }}" class="btn btn-sm btn-light border text-primary fw-semibold w-100 py-2">
                            📥 Download Template Resmi Impor DPL (.xlsx)
                        </a>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border fs-8">
                        <strong class="d-block mb-1 text-dark">📋 Urutan Kolom Template Impor DPL:</strong>
                        <ol class="mb-0 ps-3 text-secondary">
                            <li><code>ID DPL</code> (Dikosongkan saat tambah data baru)</li>
                            <li><code>Username</code> (Opsional, dibuat otomatis jika kosong)</li>
                            <li><code>Password</code> (Default: <code>password123</code> jika dikosongkan)</li>
                            <li><code>NIP / NIDN</code> (Opsional)</li>
                            <li><code>Nama Lengkap DPL</code> (Wajib)</li>
                            <li><code>No HP / Whatsapp</code> (Opsional)</li>
                            <li><code>Email</code> (Opsional)</li>
                            <li><code>Status Akun</code> (Default: <code>Aktif</code>)</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <label for="file_excel_dpl" class="form-label fw-semibold text-secondary">Pilih Berkas Excel:</label>
                        <input type="file" name="file_excel" id="file_excel_dpl" class="form-control" accept=".xlsx,.xls,.csv" required>
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
