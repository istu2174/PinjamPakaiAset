<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard peminjam dengan statistik dan aktivitas terkini.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $stats = [
            'total_pengajuan'  => Peminjaman::where('peminjam_id', $userId)->count(),
            'sedang_dipinjam'  => Peminjaman::where('peminjam_id', $userId)->where('status', 'dipinjam')->count(),
            'menunggu_proses'  => Peminjaman::where('peminjam_id', $userId)->whereIn('status', ['pending', 'diverifikasi'])->count(),
            'selesai'          => Peminjaman::where('peminjam_id', $userId)->where('status', 'selesai')->count(),
        ];

        // Peminjaman aktif
        $peminjamanAktif = Peminjaman::with('aset')
            ->where('peminjam_id', $userId)
            ->aktif()
            ->latest()
            ->take(3)
            ->get();

        // Notifikasi terbaru yang belum dibaca
        $notifikasiTerbaru = Auth::user()
            ->notifikasis()
            ->belumDibaca()
            ->latest()
            ->take(5)
            ->get();

        return view('peminjam.dashboard', compact('stats', 'peminjamanAktif', 'notifikasiTerbaru'));
    }
}
