<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Daftar semua pengajuan peminjaman untuk admin.
     */
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['peminjam', 'aset'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_pengajuan', 'like', "%{$request->search}%")
                  ->orWhereHas('peminjam', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        $peminjamans = $query->paginate(15)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Detail satu peminjaman.
     */
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load([
            'peminjam', 'aset.kategori', 'aset.kondisiTerbaru',
            'surat', 'pengembalian', 'keluhanss',
            'diverifikasiOleh', 'disetujuiOleh',
        ]);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }
}
