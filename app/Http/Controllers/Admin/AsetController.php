<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsetController extends Controller
{
    /**
     * Daftar semua aset dengan filter kondisi dan status.
     */
    public function index(Request $request): View
    {
        $query = Aset::with('kategori')->latest();

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bisa_dipinjam')) {
            $query->where('bisa_dipinjam', (bool) $request->bisa_dipinjam);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_aset', 'like', "%{$request->search}%")
                  ->orWhere('kode_aset', 'like', "%{$request->search}%");
            });
        }

        $asets     = $query->paginate(15)->withQueryString();
        $kategoris = KategoriAset::orderBy('nama')->get();

        return view('admin.aset.index', compact('asets', 'kategoris'));
    }

    /**
     * Detail satu aset beserta riwayat kondisi dan peminjaman.
     */
    public function show(Aset $aset): View
    {
        $aset->load(['kategori', 'kondisiTerbaru.pengelola', 'kondisiAsets.pengelola']);
        $riwayatPeminjaman = $aset->peminjamans()->with('peminjam')->latest()->paginate(10);

        return view('admin.aset.show', compact('aset', 'riwayatPeminjaman'));
    }

    /**
     * Form tambah aset baru.
     */
    public function create(): View
    {
        $kategoris = KategoriAset::orderBy('nama')->get();

        return view('admin.aset.create', compact('kategoris'));
    }

    /**
     * Simpan aset baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id'     => ['required', 'exists:kategori_asets,id'],
            'kode_aset'       => ['required', 'string', 'unique:asets,kode_aset'],
            'nama_aset'       => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'tahun_pengadaan' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'lokasi'          => ['nullable', 'string'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'bisa_dipinjam'   => ['nullable', 'boolean'],
            'foto'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['bisa_dipinjam'] = $request->boolean('bisa_dipinjam', true);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('aset', 'public');
        }

        Aset::create($validated);

        return redirect()->route('admin.aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Form edit aset.
     */
    public function edit(Aset $aset): View
    {
        $kategoris = KategoriAset::orderBy('nama')->get();

        return view('admin.aset.edit', compact('aset', 'kategoris'));
    }

    /**
     * Update data aset.
     */
    public function update(Request $request, Aset $aset): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id'     => ['required', 'exists:kategori_asets,id'],
            'nama_aset'       => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'tahun_pengadaan' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'lokasi'          => ['nullable', 'string'],
            'jumlah'          => ['required', 'integer', 'min:1'],
            'bisa_dipinjam'   => ['nullable', 'boolean'],
            'status'          => ['required', 'in:tersedia,dipinjam,maintenance,tidak_aktif'],
            'foto'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['bisa_dipinjam'] = $request->boolean('bisa_dipinjam', false);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('aset', 'public');
        }

        $aset->update($validated);

        return redirect()->route('admin.aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Hapus aset (hanya jika tidak ada peminjaman aktif).
     */
    public function destroy(Aset $aset): RedirectResponse
    {
        if ($aset->peminjamans()->aktif()->exists()) {
            return back()->with('error', 'Aset tidak dapat dihapus karena masih ada peminjaman aktif.');
        }

        $aset->delete();

        return redirect()->route('admin.aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // Kategori Aset
    // -------------------------------------------------------------------------

    public function indexKategori(): View
    {
        $kategoris = KategoriAset::withCount('asets')->latest()->paginate(15);

        return view('admin.aset.kategori', compact('kategoris'));
    }

    public function storeKategori(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'      => ['required', 'string', 'unique:kategori_asets,nama'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        KategoriAset::create($request->only('nama', 'deskripsi'));

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, KategoriAset $kategori): RedirectResponse
    {
        $request->validate([
            'nama'      => ['required', 'string', 'unique:kategori_asets,nama,' . $kategori->id],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $kategori->update($request->only('nama', 'deskripsi'));

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyKategori(KategoriAset $kategori): RedirectResponse
    {
        if ($kategori->asets()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih ada aset yang menggunakannya.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
