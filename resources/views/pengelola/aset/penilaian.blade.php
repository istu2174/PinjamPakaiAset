<x-app-layout>
    <x-slot name="title">Penilaian Decision Tree</x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pengelola.aset.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Daftar Aset
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Evaluasi Decision Tree Aset</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Penilaian otomatis klasifikasi kelayakan aset berdasarkan algoritma Pohon Keputusan</p>
        </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Penilaian --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <div class="flex items-center gap-3.5 mb-6 p-4 bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-transparent border border-blue-200/60 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0 shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-800">{{ $aset->nama_aset }}</p>
                        <p class="text-xs font-semibold text-blue-600 mt-0.5">Kode: {{ $aset->kode_aset }} · Kategori: {{ $aset->kategori->nama ?? '-' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('pengelola.aset.penilaian.simpan', $aset) }}" class="space-y-6">
                    @csrf

                    {{-- Kondisi Fisik --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kondisi Fisik Barang <span class="text-rose-500">*</span>
                            <span class="ml-1 text-[11px] text-slate-400 font-normal lowercase">(Skala 1 = Sangat Rusak, 5 = Sangat Baik)</span>
                        </label>
                        <div class="flex gap-2">
                            @foreach([1,2,3,4,5] as $n)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="kondisi_fisik" value="{{ $n }}" class="peer sr-only" required>
                                    <div class="text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                                peer-checked:border-blue-600 peer-checked:bg-blue-500/10 peer-checked:text-blue-700
                                                hover:border-blue-300 transition-all">
                                        {{ $n }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Usia Aset --}}
                    <div>
                        <label for="usia_aset" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Usia Penggunaan Aset (Tahun) <span class="text-rose-500">*</span>
                        </label>
                        <input id="usia_aset" name="usia_aset" type="number" min="0" value="{{ old('usia_aset', $aset->tahun_pengadaan ? now()->year - $aset->tahun_pengadaan : '') }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                               placeholder="Contoh: 3">
                    </div>

                    {{-- Perawatan Rutin --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Riwayat Perawatan & Servis Rutin <span class="text-rose-500">*</span></label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="riwayat_perawatan_rutin" value="1" class="peer sr-only" required>
                                <div class="w-full text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                            peer-checked:border-emerald-600 peer-checked:bg-emerald-500/10 peer-checked:text-emerald-700 transition-all">
                                    ✓ Ya, Rutin Dirawat
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="riwayat_perawatan_rutin" value="0" class="peer sr-only">
                                <div class="w-full text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                            peer-checked:border-rose-600 peer-checked:bg-rose-500/10 peer-checked:text-rose-700 transition-all">
                                    ✕ Tidak / Jarang
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Frekuensi Penggunaan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Frekuensi Intensitas Pemakaian <span class="text-rose-500">*</span>
                            <span class="ml-1 text-[11px] text-slate-400 font-normal lowercase">(Skala 1 = Jarang, 5 = Sangat Tinggi)</span>
                        </label>
                        <div class="flex gap-2">
                            @foreach([1,2,3,4,5] as $n)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="frekuensi_penggunaan" value="{{ $n }}" class="peer sr-only" required>
                                    <div class="text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                                peer-checked:border-indigo-600 peer-checked:bg-indigo-500/10 peer-checked:text-indigo-700
                                                hover:border-indigo-300 transition-all">
                                        {{ $n }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Masih Bisa Diperbaiki --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Apakah Aset Masih Kelayakan Diperbaiki? <span class="text-rose-500">*</span></label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="masih_bisa_diperbaiki" value="1" class="peer sr-only" required>
                                <div class="w-full text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                            peer-checked:border-emerald-600 peer-checked:bg-emerald-500/10 peer-checked:text-emerald-700 transition-all">
                                    ✓ Masih Bisa
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="masih_bisa_diperbaiki" value="0" class="peer sr-only">
                                <div class="w-full text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500
                                            peer-checked:border-rose-600 peer-checked:bg-rose-500/10 peer-checked:text-rose-700 transition-all">
                                    ✕ Tidak Bisa Lagi
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Hasil Observasi (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="2"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"
                                  placeholder="Tambahkan catatan khusus kondisi fisik barang...">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                        Proses Algoritma Decision Tree →
                    </button>
                </form>
            </div>
        </div>

        {{-- Riwayat Penilaian --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Riwayat Evaluasi Aset</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($riwayatPenilaian as $r)
                        <div class="px-5 py-3.5">
                            <div class="flex items-center justify-between mb-1">
                                @php
                                    $dtBadge = match($r->hasil_decision_tree) {
                                        'layak_pakai'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'perlu_perawatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'layak_lelang'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default           => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full border {{ $dtBadge }}">
                                    {{ str_replace('_',' ',ucfirst($r->hasil_decision_tree)) }}
                                </span>
                                <span class="text-[10px] font-medium text-slate-400">{{ $r->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-xs font-medium text-slate-600">Fisik: <strong class="text-slate-800">{{ $r->kondisi_fisik }}/5</strong> · Usia: <strong class="text-slate-800">{{ $r->usia_aset }} Thn</strong></p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Assessor: {{ $r->pengelola->name ?? '-' }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Belum ada riwayat penilaian</div>
                    @endforelse
                </div>
            </div>

            {{-- Decision Tree Info Box --}}
            <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-5">
                <p class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Aturan Decision Tree</p>
                <div class="text-xs font-medium text-blue-800 space-y-1.5 leading-relaxed">
                    <p>🟢 <strong>Layak Pakai</strong>: Kondisi fisik ≥ 3, usia &lt; 10 thn & riwayat perawatan baik.</p>
                    <p>🟡 <strong>Perlu Perawatan</strong>: Kondisi fisik &lt; 3 namun masih bisa diperbaiki.</p>
                    <p>🔴 <strong>Layak Lelang</strong>: Kondisi fisik &lt; 2, usia &gt; 10 thn & tidak dapat diperbaiki.</p>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>


