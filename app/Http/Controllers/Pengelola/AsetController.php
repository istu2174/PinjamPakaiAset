<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\KondisiAset;
use App\Services\DecisionTreeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AsetController extends Controller
{
    public function __construct(
        private readonly DecisionTreeService $decisionTreeService
    ) {}

    /**
     * Daftar aset yang ada — pengelola bisa lihat & update kondisi.
     */
    public function index(Request $request): View
    {
        $query = Aset::with(['kategori', 'kondisiTerbaru'])->latest();

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $asets = $query->paginate(15)->withQueryString();

        return view('pengelola.aset.index', compact('asets'));
    }

    /**
     * Form penilaian kondisi aset (input kriteria Decision Tree).
     */
    public function formPenilaian(Aset $aset): View
    {
        $riwayatPenilaian = $aset->kondisiAsets()->with('pengelola')->latest()->take(5)->get();

        return view('pengelola.aset.penilaian', compact('aset', 'riwayatPenilaian'));
    }

    /**
     * Proses penilaian kondisi aset menggunakan algoritma Decision Tree.
     * Hasil klasifikasi otomatis diupdate ke kolom kondisi di tabel asets.
     */
    public function simpanPenilaian(Request $request, Aset $aset): RedirectResponse
    {
        $validated = $request->validate([
            'kondisi_fisik'           => ['required', 'integer', 'min:1', 'max:5'],
            'usia_aset'               => ['required', 'integer', 'min:0'],
            'riwayat_perawatan_rutin' => ['required', 'boolean'],
            'frekuensi_penggunaan'    => ['required', 'integer', 'min:1', 'max:5'],
            'masih_bisa_diperbaiki'   => ['required', 'boolean'],
            'catatan'                 => ['nullable', 'string'],
        ]);

        $validated['kategori_nama'] = $aset->kategori->nama ?? '';

        // Jalankan algoritma Decision Tree
        $hasil = $this->decisionTreeService->klasifikasi($validated);


        // Simpan record penilaian
        KondisiAset::create([
            'aset_id'                 => $aset->id,
            'pengelola_id'            => Auth::id(),
            'kondisi_fisik'           => $validated['kondisi_fisik'],
            'usia_aset'               => $validated['usia_aset'],
            'riwayat_perawatan_rutin' => $validated['riwayat_perawatan_rutin'],
            'frekuensi_penggunaan'    => $validated['frekuensi_penggunaan'],
            'masih_bisa_diperbaiki'   => $validated['masih_bisa_diperbaiki'],
            'hasil_decision_tree'     => $hasil['hasil'],
            'catatan'                 => $validated['catatan'] ?? null,
        ]);

        // Update kondisi aset berdasarkan hasil Decision Tree
        $statusBaru = $hasil['hasil'] === 'perlu_perawatan' ? 'maintenance' : $aset->status;

        $aset->update([
            'kondisi' => $hasil['hasil'],
            'status'  => $statusBaru,
        ]);

        return redirect()->route('pengelola.aset.index')
            ->with('success', "Penilaian berhasil disimpan. Hasil Decision Tree: {$hasil['label']}. {$hasil['keterangan']}");
    }
}
