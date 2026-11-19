<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Keluhan;
use App\Models\Peminjaman;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard pengelola dengan statistik yang relevan.
     */
    public function index(): View
    {
        $stats = [
            'pengajuan_baru'     => Peminjaman::pending()->count(),
            'menunggu_verifikasi' => Peminjaman::pending()->count(),
            'sudah_diverifikasi' => Peminjaman::where('status', 'diverifikasi')->count(),
            'aset_tersedia'      => Aset::tersedia()->count(),
            'aset_dipinjam'      => Aset::where('status', 'dipinjam')->count(),
            'aset_maintenance'   => Aset::where('status', 'maintenance')->count(),
            'keluhan_pending'    => Keluhan::pending()->count(),
        ];

        // Pengajuan terbaru yang perlu ditangani
        $pengajuanBaru = Peminjaman::with(['peminjam', 'aset'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        // Keluhan yang belum selesai
        $keluhanBaru = Keluhan::with('user')
            ->belumSelesai()
            ->latest()
            ->take(5)
            ->get();

        return view('pengelola.dashboard', compact('stats', 'pengajuanBaru', 'keluhanBaru'));
    }
}
