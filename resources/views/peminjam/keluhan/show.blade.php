<x-app-layout>
    <x-slot name="title">Detail Keluhan</x-slot>

    <div class="mb-6">
        <a href="{{ route('peminjam.keluhan.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
            ← Kembali ke Daftar Keluhan
        </a>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $keluhan->judul }}</h2>
        <p class="text-xs font-medium text-slate-400 mt-0.5">Dilaporkan pada {{ $keluhan->created_at->format('d F Y H:i') }}</p>
    </div>

    <div class="max-w-2xl space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Isi Laporan Keluhan</h3>
                @php
                    $badgeClass = match($keluhan->status) {
                        'pending'  => 'bg-rose-50 text-rose-700 border-rose-200',
                        'diproses' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        default    => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                @endphp
                <span class="px-3 py-1 text-[11px] font-bold rounded-full border {{ $badgeClass }} capitalize">
                    {{ ucfirst($keluhan->status) }}
                </span>
            </div>

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
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-bold text-emerald-900 text-xs uppercase tracking-wider">Tanggapan & Penanganan Pengelola</p>
                </div>
                <p class="text-slate-700 text-sm font-medium leading-relaxed mt-1">{{ $keluhan->respon_pengelola }}</p>
                <p class="text-[11px] font-medium text-emerald-700/80 mt-3 pt-2 border-t border-emerald-200/50">
                    Direspon pada {{ $keluhan->direspon_at?->format('d F Y H:i') }}
                </p>
            </div>
        @else
            <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 flex items-center gap-3">
                <span class="text-xl">⏳</span>
                <p class="text-xs font-semibold text-amber-800 leading-relaxed">
                    Laporan keluhan Anda saat ini sedang diperiksa dan dalam proses penanganan oleh tim pengelola Dispora.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>

