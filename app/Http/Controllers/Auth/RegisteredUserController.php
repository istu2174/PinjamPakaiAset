<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PrioritySchedulingService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * User baru selalu dibuat dengan role 'peminjam'.
     * Priority score dihitung otomatis berdasarkan jenis peminjam.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'nik'            => ['required', 'string', 'digits:16', 'regex:/^[0-9]{16}$/', 'unique:users,nik'],
            'no_hp'          => ['required', 'string', 'max:15'],
            'alamat'         => ['required', 'string'],
            'jenis_peminjam' => ['required', 'in:pemerintah,organisasi,umum'],
            'foto_ktp'       => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits'   => 'NIK harus tepat 16 digit angka, tidak boleh kurang atau lebih.',
            'nik.regex'    => 'NIK hanya boleh memuat digit angka (0-9).',
            'nik.unique'   => 'NIK ini sudah terdaftar dalam sistem SIPANDA.',
        ]);

        // Upload foto KTP ke storage/public/ktp
        $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');

        // Hitung skor prioritas berdasarkan jenis peminjam (Priority Scheduling)
        $priorityScore = PrioritySchedulingService::getSkor($request->jenis_peminjam);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => $request->password, // di-hash via User::$casts
            'role'           => 'peminjam',
            'nik'            => $request->nik,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'jenis_peminjam' => $request->jenis_peminjam,
            'priority_score' => $priorityScore,
            'foto_ktp'       => $fotoKtpPath,
            'is_active'      => false, // Wajib diverifikasi admin terlebih dahulu
        ]);

        event(new Registered($user));

        // Jangan langsung login. Arahkan ke halaman login dengan info menunggu verifikasi admin
        return redirect()->route('login')
            ->with('status', 'Registrasi berhasil! Akun Anda sedang menunggu validasi/verifikasi oleh Admin Dispora. Anda baru dapat login setelah akun diverifikasi.');
    }
}
