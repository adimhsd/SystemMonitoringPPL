<?php

namespace App\Http\Controllers\KetuaKelompok;

use App\Http\Controllers\Controller;
use App\Models\KelompokPpl;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $ketua = Auth::user();
        $kelompok = KelompokPpl::with(['mitra', 'dpl', 'anggota', 'kegiatanHarian'])
            ->where('ketua_user_id', $ketua->id)
            ->first();

        return view('ketua.dashboard', compact('ketua', 'kelompok'));
    }
}
