<?php

namespace App\Http\Controllers\PicMitra;

use App\Http\Controllers\Controller;
use App\Models\KelompokPpl;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pic = Auth::user();
        $mitra = Mitra::where('pic_user_id', $pic->id)->first();
        
        $kelompok = $mitra ? KelompokPpl::with(['ketua', 'anggota', 'dpl'])
            ->where('mitra_id', $mitra->id)
            ->first() : null;

        return view('pic.dashboard', compact('pic', 'mitra', 'kelompok'));
    }
}
