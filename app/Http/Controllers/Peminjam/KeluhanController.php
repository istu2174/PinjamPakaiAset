<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KeluhanController extends Controller
{
    /**
     * Daftar keluhan yang diajukan user yang login.
     */
    public function index(): View
    {
        $keluhanss = Keluhan::where('user_id', Auth::id())
            ->with('peminjaman.aset')
            ->latest()
            ->paginate(10);

        return view('peminjam.keluhan.index', compact('keluhanss'));
    }

    /**
     * Form tambah keluhan baru.
     */
    public function create(): View
    {
        // Hanya peminjaman yang aktif/selesai yang bisa dijadikan referensi keluhan
        $peminjamanList = Peminjaman::where('peminjam_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'selesai', 'disetujui'])
            ->with('aset')
            ->latest()
            ->get();

        return view('peminjam.keluhan.create', compact('peminjamanList'));
    }

    /**
     * Simpan keluhan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peminjaman_id' => ['nullable', 'exists:peminjamans,id'],
            'judul'         => ['required', 'string', 'max:255'],
            'isi'           => ['required', 'string', 'min:20'],
            'lampiran'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Validasi bahwa peminjaman_id (jika diisi) milik user yang login
        if (! empty($validated['peminjaman_id'])) {
            $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);
            abort_if($peminjaman->peminjam_id !== Auth::id(), 403);
        }

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('keluhan', 'public');
        }

        Keluhan::create([
            'user_id'       => Auth::id(),
            'peminjaman_id' => $validated['peminjaman_id'] ?? null,
            'judul'         => $validated['judul'],
            'isi'           => $validated['isi'],
            'lampiran'      => $lampiranPath,
        ]);

        return redirect()->route('peminjam.keluhan.index')
            ->with('success', 'Keluhan berhasil dikirim. Kami akan segera merespons.');
    }

    /**
     * Detail satu keluhan beserta respons pengelola.
     */
    public function show(Keluhan $keluhan): View
    {
        abort_if($keluhan->user_id !== Auth::id(), 403);

        $keluhan->load(['peminjaman.aset', 'ditanganiOleh']);

        return view('peminjam.keluhan.show', compact('keluhan'));
    }
}
