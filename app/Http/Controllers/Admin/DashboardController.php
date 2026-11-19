<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Keluhan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik keseluruhan sistem.
     */
    public function index(): View
    {
        $stats = [
            'total_peminjam'   => User::peminjam()->aktif()->count(),
            'peminjam_pending' => User::peminjam()->where('is_active', false)->count(),
            'total_aset'       => Aset::count(),
            'aset_tersedia'    => Aset::tersedia()->count(),
            'aset_dipinjam'    => Aset::where('status', 'dipinjam')->count(),
            'aset_maintenance' => Aset::where('status', 'maintenance')->count(),

            'peminjaman_pending'     => Peminjaman::pending()->count(),
            'peminjaman_disetujui'   => Peminjaman::disetujui()->count(),
            'peminjaman_aktif'       => Peminjaman::aktif()->count(),
            // Rekap bulan ini — tidak menghitung akun yang dikecualikan dari rekap
            'peminjaman_bulan_ini'   => Peminjaman::masukRekap()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'akun_dikecualikan'      => User::peminjam()->dikecualikanRekap()->count(),

            'keluhan_pending'  => Keluhan::pending()->count(),
            'total_pengelola'  => User::pengelola()->count(),
        ];

        // Pengajuan terbaru (5 terakhir)
        $pengajuanTerbaru = Peminjaman::with(['peminjam', 'aset'])
            ->latest()
            ->take(5)
            ->get();

        // Keluhan terbaru yang belum ditangani
        $keluhanTerbaru = Keluhan::with('user')
            ->belumSelesai()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pengajuanTerbaru', 'keluhanTerbaru'));
    }
}
