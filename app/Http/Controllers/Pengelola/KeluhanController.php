<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KeluhanController extends Controller
{
    /**
     * Daftar semua keluhan yang masuk.
     */
    public function index(Request $request): View
    {
        $query = Keluhan::with(['user', 'peminjaman'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $keluhanss = $query->paginate(15)->withQueryString();

        return view('pengelola.keluhan.index', compact('keluhanss'));
    }

    /**
     * Detail satu keluhan.
     */
    public function show(Keluhan $keluhan): View
    {
        $keluhan->load(['user', 'peminjaman.aset', 'ditanganiOleh']);

        return view('pengelola.keluhan.show', compact('keluhan'));
    }

    /**
     * Pengelola membalas/merespons keluhan.
     */
    public function respon(Request $request, Keluhan $keluhan): RedirectResponse
    {
        $request->validate([
            'respon_pengelola' => ['required', 'string'],
            'status'           => ['required', 'in:diproses,selesai'],
        ]);

        $keluhan->update([
            'ditangani_oleh'   => Auth::id(),
            'respon_pengelola' => $request->respon_pengelola,
            'status'           => $request->status,
            'direspon_at'      => now(),
        ]);

        // Kirim notifikasi ke pelapor
        Notifikasi::create([
            'user_id'       => $keluhan->user_id,
            'peminjaman_id' => $keluhan->peminjaman_id,
            'judul'         => 'Keluhan Anda Telah Direspons',
            'pesan'         => "Keluhan '{$keluhan->judul}' telah direspons oleh pengelola. Silakan cek halaman keluhan untuk melihat balasan.",
            'tipe'          => 'keluhan',
        ]);

        return redirect()->route('pengelola.keluhan.show', $keluhan)
            ->with('success', 'Respons berhasil dikirim.');
    }
}
