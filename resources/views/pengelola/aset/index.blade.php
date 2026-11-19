<x-app-layout>
    <x-slot name="title">Daftar Penilaian Aset</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Evaluasi Decision Tree Aset</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Daftar seluruh barang & peralatan dinas untuk evaluasi kondisi fisik</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">Aset</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Stok Tersedia</th>
                        <th class="px-5 py-3.5">Hasil Decision Tree</th>
                        <th class="px-5 py-3.5">Status Master</th>
                        <th class="px-5 py-3.5 text-right">Aksi Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($asets as $aset)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-extrabold text-slate-800 text-sm">{{ $aset->nama_aset }}</p>
                                <p class="text-[10px] text-slate-400 font-mono font-bold mt-0.5">{{ $aset->kode_aset }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-600">{{ $aset->kategori->nama ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <p class="font-bold {{ $aset->stok_tersedia > 0 ? 'text-emerald-700' : 'text-rose-600' }}">{{ $aset->stok_tersedia }} unit</p>
                                <p class="text-[10px] text-slate-400">Total: {{ $aset->jumlah }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($aset->kondisiTerbaru)
                                    @php
                                        $dtBadge = match($aset->kondisiTerbaru->hasil_decision_tree) {
                                            'layak_pakai'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'perlu_perawatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'layak_lelang'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default           => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full border {{ $dtBadge }}">
                                        {{ str_replace('_',' ',ucfirst($aset->kondisiTerbaru->hasil_decision_tree)) }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 text-slate-400 rounded-full border border-slate-200">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusBadge = match($aset->status) {
                                        'tersedia'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'dipinjam'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default       => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $statusBadge }} capitalize">
                                    {{ ucfirst($aset->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('pengelola.aset.penilaian', $aset) }}"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    Evaluasi DT →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-xs font-medium text-slate-400">Belum ada aset terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asets->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $asets->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>

