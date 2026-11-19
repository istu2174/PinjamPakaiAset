<x-app-layout>
    <x-slot name="title">Keluhan Saya</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Keluhan Aset</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Keluhan dan laporan masalah yang telah Anda sampaikan</p>
        </div>
        <a href="{{ route('peminjam.keluhan.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-amber-500/20 active:scale-[0.99]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan Keluhan Baru
        </a>
    </div>

    <div class="space-y-3.5">
        @forelse($keluhanss as $k)
            <a href="{{ route('peminjam.keluhan.show', $k) }}"
               class="block bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 hover:border-amber-500/50 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 group-hover:text-amber-600 transition-colors">{{ $k->judul }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1 leading-relaxed">{{ Str::limit($k->isi, 90) }}</p>
                    </div>
                    @php
                        $badgeClass = match($k->status) {
                            'pending'  => 'bg-rose-50 text-rose-700 border-rose-200',
                            'dipproses'=> 'bg-amber-50 text-amber-700 border-amber-200',
                            'selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default    => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="px-3 py-1 text-[11px] font-bold rounded-full border {{ $badgeClass }} flex-shrink-0 capitalize">
                        {{ ucfirst($k->status) }}
                    </span>
                </div>
                <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-medium text-slate-400">
                    <span>{{ $k->created_at->diffForHumans() }}</span>
                    <span class="text-amber-600 font-semibold group-hover:translate-x-1 transition-transform">Lihat Tanggapan →</span>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-200/80">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum ada keluhan dilaporkan</h3>
                <p class="text-slate-400 text-xs font-medium mt-1 mb-4">Apabila terjadi kendala peminjaman aset, sampaikan di sini</p>
                <a href="{{ route('peminjam.keluhan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-xs font-bold rounded-xl hover:bg-amber-600 transition-all shadow-sm">
                    Laporkan Keluhan Sekarang →
                </a>
            </div>
        @endforelse
    </div>

    @if($keluhanss->hasPages())
        <div class="mt-6">{{ $keluhanss->withQueryString()->links() }}</div>
    @endif
</x-app-layout>

