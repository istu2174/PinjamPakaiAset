<x-app-layout>
    <x-slot name="title">Detail Keluhan</x-slot>

    <div class="mb-6">
        <a href="{{ route('pengelola.keluhan.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
            ← Kembali ke Daftar Keluhan
        </a>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $keluhan->judul }}</h2>
        <p class="text-xs font-medium text-slate-400 mt-0.5">Dilaporkan pada {{ $keluhan->created_at->format('d F Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Isi Laporan Keluhan</h3>
                <p class="text-slate-700 text-sm font-medium leading-relaxed">{{ $keluhan->isi }}</p>
                @if($keluhan->lampiran)
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ Storage::url($keluhan->lampiran) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Lihat Berkas Lampiran
                        </a>
                    </div>
                @endif
            </div>

            @if($keluhan->respon_pengelola)
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-6 shadow-sm">
                    <p class="font-bold text-emerald-900 text-xs uppercase tracking-wider mb-2">Respons Pengelola Sebelumnya</p>
                    <p class="text-slate-700 text-sm font-medium leading-relaxed">{{ $keluhan->respon_pengelola }}</p>
                    <p class="text-[11px] font-medium text-emerald-700/80 mt-3 pt-2 border-t border-emerald-200/50">
                        Ditangani oleh {{ $keluhan->ditanganiOleh->name ?? '-' }} · {{ $keluhan->direspon_at?->format('d/m/Y H:i') }}
                    </p>
                </div>
            @endif

            @if($keluhan->status !== 'selesai')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Form Respons & Tindakan Pengelola</h3>
                    <form method="POST" action="{{ route('pengelola.keluhan.respon', $keluhan) }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Respons / Solusi Penanganan <span class="text-rose-500">*</span></label>
                            <textarea name="respon_pengelola" rows="4" required
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"
                                      placeholder="Tuliskan jawaban, tindakan perbaikan, atau solusi untuk peminjam..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Update Status Keluhan</label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="diproses" class="peer sr-only" required>
                                    <div class="text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500 peer-checked:border-amber-600 peer-checked:bg-amber-500/10 peer-checked:text-amber-700 transition-all">
                                        ⚡ Sedang Diproses
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="selesai" class="peer sr-only">
                                    <div class="text-center py-3 border-2 border-slate-200 rounded-xl text-xs font-extrabold text-slate-500 peer-checked:border-emerald-600 peer-checked:bg-emerald-500/10 peer-checked:text-emerald-700 transition-all">
                                        ✔ Selesai Ditangani
                                    </div>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                            Kirim Respons & Update Status →
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4 text-xs font-medium">
                <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelapor</p>
                    <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $keluhan->user->name ?? '-' }}</p>
                </div>
                <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Laporan</p>
                    @php
                        $badgeClass = match($keluhan->status) {
                            'pending'  => 'bg-rose-50 text-rose-700 border-rose-200',
                            'diproses' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default    => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $badgeClass }} capitalize">
                        {{ ucfirst($keluhan->status) }}
                    </span>
                </div>
                <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Dilaporkan</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $keluhan->created_at->format('d F Y H:i') }}</p>
                </div>
                @if($keluhan->peminjaman)
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terkait Peminjaman</p>
                        <p class="font-mono font-bold text-blue-600 mt-0.5">{{ $keluhan->peminjaman->nomor_pengajuan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

