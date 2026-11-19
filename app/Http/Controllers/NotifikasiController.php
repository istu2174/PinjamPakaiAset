<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi milik user yang sedang login.
     */
    public function index(): View
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->with('peminjaman')
            ->latest()
            ->paginate(15);

        // Tandai semua sebagai sudah dibaca saat halaman dibuka
        Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true, 'dibaca_at' => now()]);

        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Kembalikan jumlah notifikasi yang belum dibaca (untuk badge di sidebar).
     * Dipanggil via AJAX.
     */
    public function unreadCount(): JsonResponse
    {
        $count = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(int $id): RedirectResponse
    {
        Notifikasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['dibaca' => true, 'dibaca_at' => now()]);

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true, 'dibaca_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
