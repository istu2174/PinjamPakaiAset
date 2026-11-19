<x-app-layout>
    <x-slot name="title">Detail Aset</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.aset.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Manajemen Aset
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $aset->nama_aset }}</h2>
            <p class="text-xs font-mono font-bold text-slate-400 mt-0.5">{{ $aset->kode_aset }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.aset.edit', $aset) }}"
               class="px-4 py-2.5 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">Edit Aset</a>
            <form method="POST" action="{{ route('admin.aset.destroy', $aset) }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2.5 border border-rose-200 text-rose-600 font-bold text-xs rounded-xl hover:bg-rose-50 transition-all">Hapus Aset</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Foto aset --}}
            @if($aset->foto)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <img src="{{ Storage::url($aset->foto) }}" alt="{{ $aset->nama_aset }}"
                         class="w-full h-56 object-cover">
                </div>
            @endif

            {{-- Info Aset --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Spesifikasi & Informasi Detail</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori Aset</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $aset->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode Inventaris</p>
                        <p class="font-mono font-bold text-slate-800 text-sm mt-0.5">{{ $aset->kode_aset }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Merk / Tipe / Spesifikasi</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $aset->merk ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Pengadaan</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $aset->tahun_pengadaan ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stok Unit (Total / Dipinjam / Tersedia)</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">
                            {{ $aset->jumlah }} unit <span class="text-slate-400 font-normal">(Total)</span> · 
                            <span class="text-indigo-600">{{ $aset->stok_dipinjam }} dipinjam</span> · 
                            <span class="{{ $aset->stok_tersedia > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $aset->stok_tersedia }} tersedia</span>
                        </p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hak Akses Peminjaman</p>
                        <p class="font-bold text-sm mt-0.5 {{ $aset->bisa_dipinjam ? 'text-emerald-600' : 'text-slate-600' }}">
                            {{ $aset->bisa_dipinjam ? '✓ Dapat Dipinjamkan ke Publik' : '🔒 Khusus Internal Dispora (Non-Pinjam)' }}
                        </p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100 sm:col-span-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Ruang / Gedung</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $aset->lokasi ?? '-' }}</p>
                    </div>
                    @if($aset->deskripsi)
                        <div class="sm:col-span-2 p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Lengkap</p>
                            <p class="text-slate-700 font-medium leading-relaxed">{{ $aset->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kondisi Decision Tree --}}
            @if($aset->kondisiTerbaru)
                @php $k = $aset->kondisiTerbaru; @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Hasil Klasifikasi Decision Tree Terkini</h3>
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $dtBadge = match($k->hasil_decision_tree) {
                                'layak_pakai'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'perlu_perawatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'layak_lelang'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                default           => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-full border {{ $dtBadge }}">
                            ✔ Klasifikasi: {{ str_replace('_', ' ', ucfirst($k->hasil_decision_tree)) }}
                        </span>
                        <span class="text-xs font-medium text-slate-400">Dinilai {{ $k->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Kondisi Fisik</p>
                            <p class="text-xl font-black text-slate-800 mt-0.5">{{ $k->kondisi_fisik }}<span class="text-xs font-normal text-slate-400">/5</span></p>
                        </div>
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Usia Aset</p>
                            <p class="text-xl font-black text-slate-800 mt-0.5">{{ $k->usia_aset }}<span class="text-xs font-normal text-slate-400"> Thn</span></p>
                        </div>
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Perawatan Rutin</p>
                            <p class="text-xl font-black mt-0.5 {{ $k->riwayat_perawatan_rutin ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $k->riwayat_perawatan_rutin ? '✓ Ya' : '✕ Tidak' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Riwayat Peminjaman Aset Ini</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="px-5 py-3">Peminjam</th>
                                <th class="px-5 py-3">Periode Peminjaman</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($riwayatPeminjaman as $rp)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-3 font-bold text-slate-800">{{ $rp->peminjam->name ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-400 font-medium">{{ $rp->tanggal_pinjam->format('d/m/Y') }} – {{ $rp->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3">
                                        @php
                                            $statusBadge = match($rp->status) {
                                                'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                                'ditolak'      => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default        => 'bg-slate-50 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $statusBadge }} capitalize">
                                            {{ ucfirst($rp->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-xs font-medium text-slate-400">Belum pernah dipinjam</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($riwayatPeminjaman->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100">{{ $riwayatPeminjaman->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Right: Status --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-5">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Master Aset</p>
                    @php
                        $statusBadge = match($aset->status) {
                            'tersedia'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dipinjam'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                            default       => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="inline-block px-3.5 py-1.5 text-xs font-extrabold rounded-full border {{ $statusBadge }} capitalize">
                        {{ ucfirst($aset->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Klasifikasi Kondisi</p>
                    @php
                        $dtBadge = match($aset->kondisi) {
                            'layak_pakai'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'perlu_perawatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'layak_lelang'    => 'bg-rose-50 text-rose-700 border-rose-200',
                            default           => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="inline-block px-3.5 py-1.5 text-xs font-extrabold rounded-full border {{ $dtBadge }}">
                        {{ str_replace('_',' ',ucfirst($aset->kondisi ?? 'belum dinilai')) }}
                    </span>
                </div>
                <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 font-medium space-y-1">
                    <p>Ditambahkan: {{ $aset->created_at->format('d/m/Y') }}</p>
                    <p>Terakhir Diperbarui: {{ $aset->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

