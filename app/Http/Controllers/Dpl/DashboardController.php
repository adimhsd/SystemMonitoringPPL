<?php

namespace App\Http\Controllers\Dpl;

use App\Http\Controllers\Controller;
use App\Models\KelompokPpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dpl = Auth::user();
        $kelompokBimbingan = KelompokPpl::with(['mitra', 'ketua', 'anggota'])
            ->where('dpl_id', $dpl->id)
            ->get();

        $totalMahasiswa = $kelompokBimbingan->sum(fn ($k) => $k->anggota->count());

        return view('dpl.dashboard', compact('dpl', 'kelompokBimbingan', 'totalMahasiswa'));
    }
}
