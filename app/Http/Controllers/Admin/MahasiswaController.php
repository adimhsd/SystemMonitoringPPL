<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MahasiswaExport;
use App\Http\Controllers\Controller;
use App\Imports\MahasiswaImport;
use App\Models\KelompokPpl;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan Daftar Master Data Mahasiswa.
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with('kelompok');

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('kelompok_status')) {
            if ($request->kelompok_status === 'assigned') {
                $query->whereNotNull('kelompok_id');
            } elseif ($request->kelompok_status === 'unassigned') {
                $query->whereNull('kelompok_id');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $mahasiswaList = $query->latest()->paginate(15)->withQueryString();
        $kelompokList = KelompokPpl::all();

        return view('admin.mahasiswa.index', compact('mahasiswaList', 'kelompokList'));
    }

    /**
     * Form Tambah Mahasiswa Baru Manual.
     */
    public function create()
    {
        $kelompokList = KelompokPpl::where('status', 'aktif')->get();

        return view('admin.mahasiswa.create', compact('kelompokList'));
    }

    /**
     * Simpan Data Mahasiswa Baru Manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:anggota_kelompok,nim'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'prodi' => ['required', Rule::in(['Manajemen', 'Akuntansi', 'Bisnis Digital'])],
            'kelas' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kelompok_id' => ['nullable', 'exists:kelompok_ppl,id'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar dalam sistem.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'prodi.required' => 'Program studi wajib dipilih.',
        ]);

        Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'prodi' => $request->prodi,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data Mahasiswa baru berhasil ditambahkan.');
    }

    /**
     * Form Edit Data Mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $kelompokList = KelompokPpl::where('status', 'aktif')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelompokList'));
    }

    /**
     * Update Data Mahasiswa.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', Rule::unique('anggota_kelompok', 'nim')->ignore($mahasiswa->id)],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'prodi' => ['required', Rule::in(['Manajemen', 'Akuntansi', 'Bisnis Digital'])],
            'kelas' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kelompok_id' => ['nullable', 'exists:kelompok_ppl,id'],
        ]);

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'prodi' => $request->prodi,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelompok_id' => $request->kelompok_id,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    /**
     * Hapus Data Mahasiswa.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    /**
     * Export Excel Data Mahasiswa.
     */
    public function exportExcel()
    {
        return Excel::download(new MahasiswaExport, 'Master_Data_Mahasiswa_PPL.xlsx');
    }

    /**
     * Import Excel Data Mahasiswa.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file_excel.max' => 'Ukuran file Excel maksimal 5MB.',
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file_excel'));

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data Mahasiswa dari file Excel berhasil diimpor ke sistem.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Gagal mengimpor file Excel: ' . $e->getMessage());
        }
    }
}
