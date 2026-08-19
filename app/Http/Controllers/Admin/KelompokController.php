<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelompokPpl;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelompokController extends Controller
{
    /**
     * Tampilkan Daftar Akun Master Kelompok PPL.
     */
    public function index(Request $request)
    {
        $query = KelompokPpl::with(['ketua', 'mitra', 'dpl', 'anggota']);

        if ($request->filled('tahun')) {
            $query->where('tahun_akademik', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                  ->orWhereHas('ketua', function ($uq) use ($search) {
                      $uq->where('username', 'like', "%{$search}%");
                  });
            });
        }

        $kelompokList = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kelompok.index', compact('kelompokList'));
    }

    /**
     * Form Tambah Akun Kelompok PPL Baru.
     */
    public function create()
    {
        $mitraList = Mitra::where('status_kerja_sama', 'aktif')->get();
        $dplList = User::where('role', 'dpl')->where('is_active', true)->get();

        return view('admin.kelompok.create', compact('mitraList', 'dplList'));
    }

    /**
     * Simpan Akun Kelompok PPL Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'tahun_akademik' => ['required', 'string', 'max:10'],
            'mitra_id' => ['nullable', 'exists:mitra,id'],
            'dpl_id' => ['nullable', 'exists:users,id'],
        ], [
            'nama_kelompok.required' => 'Nama kelompok wajib diisi.',
            'username.required' => 'Username akun kelompok wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.required' => 'Password akun kelompok wajib diisi.',
        ]);

        DB::transaction(function () use ($request) {
            // Create Group User Account
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'ketua_kelompok',
                'nama_lengkap' => $request->nama_kelompok,
                'must_change_password' => false,
                'is_active' => true,
            ]);

            // Create Kelompok Record
            KelompokPpl::create([
                'nama_kelompok' => $request->nama_kelompok,
                'ketua_user_id' => $user->id,
                'mitra_id' => $request->mitra_id,
                'dpl_id' => $request->dpl_id,
                'tahun_akademik' => $request->tahun_akademik,
                'status' => 'aktif',
            ]);
        });

        return redirect()->route('admin.kelompok.index')
            ->with('success', 'Akun Kelompok PPL baru berhasil dibuat.');
    }

    /**
     * Tampilkan Detail Akun & Anggota Kelompok.
     */
    public function show(KelompokPpl $kelompok)
    {
        $kelompok->load(['mitra.picUser', 'dpl', 'ketua', 'anggota', 'kegiatanHarian']);

        return view('admin.kelompok.show', compact('kelompok'));
    }

    /**
     * Form Edit Akun & Kredensial Kelompok.
     */
    public function edit(KelompokPpl $kelompok)
    {
        $kelompok->load(['ketua', 'mitra', 'dpl']);

        return view('admin.kelompok.edit', compact('kelompok'));
    }

    /**
     * Update Akun & Kredensial Kelompok.
     */
    public function update(Request $request, KelompokPpl $kelompok)
    {
        $user = $kelompok->ketua;

        $request->validate([
            'nama_kelompok' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id ?? 0)],
            'password' => ['nullable', 'string', 'min:6'],
            'tahun_akademik' => ['required', 'string', 'max:10'],
            'status' => ['required', Rule::in(['aktif', 'selesai', 'dibatalkan'])],
        ]);

        DB::transaction(function () use ($request, $kelompok, $user) {
            $kelompok->update([
                'nama_kelompok' => $request->nama_kelompok,
                'tahun_akademik' => $request->tahun_akademik,
                'status' => $request->status,
            ]);

            if ($user) {
                $userPayload = [
                    'username' => $request->username,
                    'nama_lengkap' => $request->nama_kelompok,
                ];

                if ($request->filled('password')) {
                    $userPayload['password'] = Hash::make($request->password);
                }

                $user->update($userPayload);
            }
        });

        return redirect()->route('admin.kelompok.index')
            ->with('success', 'Kredensial & Akun Kelompok PPL berhasil diperbarui.');
    }

    /**
     * Hapus Akun & Data Kelompok PPL.
     */
    public function destroy(KelompokPpl $kelompok)
    {
        DB::transaction(function () use ($kelompok) {
            if ($kelompok->ketua) {
                $kelompok->ketua->delete();
            }
            $kelompok->delete();
        });

        return redirect()->route('admin.kelompok.index')
            ->with('success', 'Akun & Data Kelompok PPL berhasil dihapus.');
    }
}
