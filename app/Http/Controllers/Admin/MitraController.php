<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MitraExport;
use App\Http\Controllers\Controller;
use App\Imports\MitraImport;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MitraController extends Controller
{
    /**
     * Tampilkan daftar Master Mitra.
     */
    public function index(Request $request)
    {
        $query = Mitra::with('picUser');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $mitraList = $query->latest()->paginate(20)->withQueryString();

        return view('admin.mitra.index', compact('mitraList'));
    }

    /**
     * Form Tambah Mitra Baru.
     */
    public function create()
    {
        // PIC Mitra yang belum ditautkan ke Mitra manapun (1 PIC = 1 Mitra)
        $availablePics = User::where('role', 'pic_mitra')
            ->whereDoesntHave('mitraPic')
            ->get();

        return view('admin.mitra.create', compact('availablePics'));
    }

    /**
     * Simpan Data Mitra Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(['SKPD', 'Swasta', 'UMKM'])],
            'alamat' => ['nullable', 'string'],
            'pic_option' => ['required', Rule::in(['existing', 'new', 'none'])],
            'pic_user_id' => ['nullable', 'required_if:pic_option,existing', 'exists:users,id'],
            'new_pic_username' => ['nullable', 'required_if:pic_option,new', 'string', 'max:50', 'unique:users,username'],
            'new_pic_nama' => ['nullable', 'required_if:pic_option,new', 'string', 'max:100'],
            'new_pic_hp' => ['nullable', 'string', 'max:20'],
        ], [
            'nama_mitra.required' => 'Nama mitra wajib diisi.',
            'new_pic_username.unique' => 'Username PIC ini sudah digunakan.',
        ]);

        $picUserId = null;

        if ($request->pic_option === 'existing') {
            $picUserId = $request->pic_user_id;
        } elseif ($request->pic_option === 'new') {
            $newPic = User::create([
                'username' => $request->new_pic_username,
                'password' => Hash::make('password'),
                'role' => 'pic_mitra',
                'nama_lengkap' => $request->new_pic_nama,
                'no_hp' => $request->new_pic_hp,
                'must_change_password' => true,
                'is_active' => true,
            ]);
            $picUserId = $newPic->id;
        }

        Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'pic_user_id' => $picUserId,
        ]);

        return redirect()->route('admin.mitra.index')
            ->with('success', 'Data Mitra baru berhasil ditambahkan.');
    }

    /**
     * Form Edit Mitra.
     */
    public function edit(Mitra $mitra)
    {
        $mitra->load('picUser');

        // PIC Mitra yang belum ditautkan + PIC Mitra saat ini
        $availablePics = User::where('role', 'pic_mitra')
            ->where(function ($q) use ($mitra) {
                $q->whereDoesntHave('mitraPic')
                  ->orWhere('id', $mitra->pic_user_id);
            })
            ->get();

        return view('admin.mitra.edit', compact('mitra', 'availablePics'));
    }

    /**
     * Update Data Mitra.
     */
    public function update(Request $request, Mitra $mitra)
    {
        $request->validate([
            'nama_mitra' => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(['SKPD', 'Swasta', 'UMKM'])],
            'alamat' => ['nullable', 'string'],
            'pic_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'pic_user_id' => $request->pic_user_id,
        ]);

        return redirect()->route('admin.mitra.index')
            ->with('success', 'Data Mitra berhasil diperbarui.');
    }

    /**
     * Hapus Mitra (Soft Delete).
     */
    public function destroy(Mitra $mitra)
    {
        $mitra->delete();

        return redirect()->route('admin.mitra.index')
            ->with('success', 'Data Mitra berhasil dihapus.');
    }

    /**
     * Export Excel Data Mitra.
     */
    public function exportExcel()
    {
        return Excel::download(new MitraExport, 'Master_Data_Mitra_PPL.xlsx');
    }

    /**
     * Import Excel Data Mitra.
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
            Excel::import(new MitraImport, $request->file('file_excel'));

            return redirect()->route('admin.mitra.index')
                ->with('success', 'Data Mitra Instansi dari file Excel berhasil diimpor ke sistem.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.mitra.index')
                ->with('error', 'Gagal mengimpor file Excel Mitra: ' . $e->getMessage());
        }
    }
}
