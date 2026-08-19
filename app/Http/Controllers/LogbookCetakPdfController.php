<?php

namespace App\Http\Controllers;

use App\Models\KegiatanHarian;
use App\Models\KelompokPpl;
use App\Models\Mitra;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LogbookCetakPdfController extends Controller
{
    /**
     * Cetak Laporan Logbook Kegiatan Harian PPL ke format PDF.
     */
    public function downloadPdf(Request $request, ?KelompokPpl $kelompok = null)
    {
        $user = Auth::user();

        if ($user->role === 'ketua_kelompok') {
            $kelompok = KelompokPpl::where('ketua_user_id', $user->id)
                ->orWhereHas('anggota', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->first();

            if (! $kelompok) {
                return redirect()->back()->with('error', 'Kelompok PPL Anda belum terdaftar.');
            }
        } elseif ($user->role === 'dpl') {
            if (! $kelompok || $kelompok->dpl_id !== $user->id) {
                return redirect()->back()->with('error', 'Akses ditolak. Kelompok ini bukan bimbingan DPL Anda.');
            }
        } elseif ($user->role === 'admin') {
            if (! $kelompok) {
                return redirect()->back()->with('error', 'Silakan pilih Kelompok PPL yang akan dicetak.');
            }
        } else {
            abort(403, 'Akses ditolak.');
        }

        $kelompok->load(['mitra', 'dpl', 'ketua', 'anggota']);

        $logbookList = KegiatanHarian::where('kelompok_id', $kelompok->id)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Convert foto_dokumentasi to Base64 for DOMPDF stability
        foreach ($logbookList as $logbook) {
            $logbook->foto_base64 = null;
            if ($logbook->foto_dokumentasi) {
                $path = storage_path('app/public/' . $logbook->foto_dokumentasi);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logbook->foto_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        }

        $pdf = Pdf::loadView('pdf.laporan-logbook', [
            'kelompok' => $kelompok,
            'logbookList' => $logbookList,
            'tglCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $safeNama = str_replace(['/', '\\', ' '], '_', $kelompok->nama_kelompok);
        $filename = 'Laporan_Logbook_PPL_' . $safeNama . '_' . date('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }
}
