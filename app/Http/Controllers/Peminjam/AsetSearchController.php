<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsetSearchController extends Controller
{
    /**
     * Halaman utama pencarian aset untuk peminjam.
     * Ini adalah landing page setelah login.
     */
    public function index(Request $request): View
    {
        // Hanya ambil kategori yang memiliki aset yang bisa dipinjam
        $kategoris = KategoriAset::whereHas('asets', function ($q) {
            $q->where('bisa_dipinjam', true)->where('status', 'tersedia');
        })->withCount(['asets' => function ($q) {
            $q->where('bisa_dipinjam', true)->where('status', 'tersedia');
        }])->orderBy('nama')->get();

        $query = $request->get('q');
        $kategoriFilter = $request->get('kategori');

        // Selalu tampilkan aset yang BISA DIPAKAI & DIPINJAM saja
        $builder = Aset::with(['kategori', 'kondisiTerbaru', 'peminjamans'])
            ->bisaDipakai();

        if ($query) {
            $builder->where(function ($q) use ($query) {
                $q->where('nama_aset', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhere('merk', 'like', "%{$query}%")
                  ->orWhereHas('kategori', fn ($k) => $k->where('nama', 'like', "%{$query}%"));
            });
        }

        if ($kategoriFilter) {
            $builder->where('kategori_id', $kategoriFilter);
        }

        $asets = $builder->latest()->paginate(12)->withQueryString();

        return view('peminjam.cari', compact('kategoris', 'asets', 'query', 'kategoriFilter'));
    }
}
