<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PrioritySchedulingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar semua user dengan filter berdasarkan role.
     */
    public function index(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'menunggu_verifikasi') {
                $query->where('is_active', false)->where('role', 'peminjam');
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_active', false);
            } elseif ($request->status === 'dikecualikan_rekap') {
                $query->where('role', 'peminjam')->where('dikecualikan_rekap', true);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $pendingCount = User::where('role', 'peminjam')->where('is_active', false)->count();

        return view('admin.users.index', compact('users', 'pendingCount'));
    }

    /**
     * Detail user termasuk riwayat peminjaman.
     */
    public function show(User $user): View
    {
        $user->load(['peminjamans.aset']);
        $riwayatPeminjaman = $user->peminjamans()->latest()->paginate(10);

        return view('admin.users.show', compact('user', 'riwayatPeminjaman'));
    }

    /**
     * Form tambah user (untuk pengelola/admin).
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru (khusus admin untuk buat akun pengelola/admin lain).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:8'],
            'role'           => ['required', 'in:peminjam,pengelola,admin'],
            'nik'            => ['nullable', 'string', 'digits:16', 'regex:/^[0-9]{16}$/', 'unique:users,nik'],
            'no_hp'          => ['nullable', 'string', 'max:15'],
            'alamat'         => ['nullable', 'string'],
            'jenis_peminjam' => ['required_if:role,peminjam', 'in:pemerintah,organisasi,umum'],
            'dikecualikan_rekap'   => ['nullable', 'boolean'],
            'catatan_pengecualian' => ['nullable', 'string', 'max:500'],
        ], [
            'nik.digits' => 'NIK harus tepat 16 digit angka, tidak boleh kurang atau lebih.',
            'nik.regex'  => 'NIK hanya boleh memuat digit angka (0-9).',
            'nik.unique' => 'NIK ini sudah digunakan oleh akun lain.',
        ]);

        $priorityScore = isset($validated['jenis_peminjam'])
            ? PrioritySchedulingService::getSkor($validated['jenis_peminjam'])
            : 1;

        $validated = $this->normalisasiRekap($request, $validated);

        User::create(array_merge($validated, [
            'priority_score' => $priorityScore,
            'is_active'      => true,
        ]));

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'           => ['required', 'in:peminjam,pengelola,admin'],
            'nik'            => ['nullable', 'string', 'digits:16', 'regex:/^[0-9]{16}$/', 'unique:users,nik,' . $user->id],
            'no_hp'          => ['nullable', 'string', 'max:15'],
            'alamat'         => ['nullable', 'string'],
            'jenis_peminjam' => ['required_if:role,peminjam', 'in:pemerintah,organisasi,umum'],
            'is_active'      => ['boolean'],
            'dikecualikan_rekap'   => ['nullable', 'boolean'],
            'catatan_pengecualian' => ['nullable', 'string', 'max:500'],
        ], [
            'nik.digits' => 'NIK harus tepat 16 digit angka, tidak boleh kurang atau lebih.',
            'nik.regex'  => 'NIK hanya boleh memuat digit angka (0-9).',
            'nik.unique' => 'NIK ini sudah digunakan oleh akun lain.',
        ]);

        if (isset($validated['jenis_peminjam'])) {
            $validated['priority_score'] = PrioritySchedulingService::getSkor($validated['jenis_peminjam']);
        }

        $validated = $this->normalisasiRekap($request, $validated);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Nonaktifkan/aktifkan/verifikasi user (soft toggle, bukan delete).
     */
    public function toggleAktif(Request $request, User $user): RedirectResponse
    {
        $wasInactive = ! $user->is_active;
        $data = ['is_active' => $wasInactive];

        // Saat verifikasi akun peminjam, admin dapat sekaligus menandai
        // akun agar dikecualikan dari rekap bulanan.
        if ($wasInactive && $user->role === 'peminjam' && $request->has('dikecualikan_rekap')) {
            $data['dikecualikan_rekap'] = $request->boolean('dikecualikan_rekap');
            if ($data['dikecualikan_rekap'] && $request->filled('catatan_pengecualian')) {
                $data['catatan_pengecualian'] = $request->input('catatan_pengecualian');
            }
        }

        $user->update($data);

        $status = $user->is_active ? 'diverifikasi & diaktifkan' : 'dinonaktifkan';
        if (! empty($data['dikecualikan_rekap'])) {
            $status .= ' (ditandai: dikecualikan dari rekap bulanan)';
        }

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    /**
     * Tandai / batalkan tanda akun peminjam agar aktivitas peminjaman &
     * pengembaliannya tidak dihitung dalam rekap bulanan.
     */
    public function toggleRekap(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'peminjam') {
            return back()->with('error', 'Hanya akun peminjam yang dapat dikecualikan dari rekap.');
        }

        $request->validate([
            'catatan_pengecualian' => ['nullable', 'string', 'max:500'],
        ]);

        $dikecualikan = ! $user->dikecualikan_rekap;

        $user->update([
            'dikecualikan_rekap'   => $dikecualikan,
            'catatan_pengecualian' => $dikecualikan ? $request->input('catatan_pengecualian') : null,
        ]);

        $pesan = $dikecualikan
            ? "Akun {$user->name} ditandai: aktivitasnya tidak dihitung dalam rekap bulanan."
            : "Tanda dicabut: aktivitas akun {$user->name} kembali dihitung dalam rekap bulanan.";

        return back()->with('success', $pesan);
    }

    /**
     * Penanda rekap hanya berlaku untuk role peminjam.
     */
    private function normalisasiRekap(Request $request, array $validated): array
    {
        $isPeminjam = ($validated['role'] ?? null) === 'peminjam';

        $validated['dikecualikan_rekap'] = $isPeminjam && $request->boolean('dikecualikan_rekap');
        $validated['catatan_pengecualian'] = $validated['dikecualikan_rekap']
            ? ($validated['catatan_pengecualian'] ?? null)
            : null;

        return $validated;
    }
}
