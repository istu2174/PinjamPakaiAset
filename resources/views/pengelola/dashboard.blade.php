<x-app-layout>
    <x-slot name="title">Dashboard Pengelola Aset</x-slot>

    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Pengelola Aset</h2>
        <p class="text-xs font-medium text-slate-500 mt-1">Ringkasan verifikasi peminjaman, penilaian Decision Tree, dan keluhan aset</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $cards = [
                ['label'=>'Pengajuan Baru','value'=>$stats['pengajuan_baru'],'color'=>'amber','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Aset Tersedia','value'=>$stats['aset_tersedia'],'color'=>'green','icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['label'=>'Sedang Dipinjam','value'=>$stats['aset_dipinjam'],'color'=>'blue','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label'=>'Keluhan Pending','value'=>$stats['keluhan_pending'],'color'=>'red','icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ];
            $bgMap = ['amber'=>'bg-amber-500/10','green'=>'bg-emerald-500/10','blue'=>'bg-blue-500/10','red'=>'bg-rose-500/10'];
            $txtMap = ['amber'=>'text-amber-600','green'=>'text-emerald-600','blue'=>'text-blue-600','red'=>'text-rose-600'];
        @endphp
        @foreach($cards as $card)
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $card['label'] }}</span>
                    <div class="w-9 h-9 rounded-xl {{ $bgMap[$card['color']] }} flex items-center justify-center">
                        <svg class="w-4 h-4 {{ $txtMap[$card['color']] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pengajuan Baru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Perlu Verifikasi Pengelola</h3>
                <a href="{{ route('pengelola.peminjaman.index') }}?status=pending" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pengajuanBaru as $p)
                    <a href="{{ route('pengelola.peminjaman.show', $p) }}"
                       class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition group">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition truncate">{{ $p->peminjam->name ?? '-' }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $p->aset->nama_aset ?? '-' }} · {{ $p->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="ml-3 px-2.5 py-1 text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 rounded-full flex-shrink-0">
                            ⚡ Prioritas: {{ $p->priority_score }}/3
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Tidak ada pengajuan baru yang membutuhkan verifikasi</div>
                @endforelse
            </div>
        </div>

        {{-- Keluhan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Keluhan Aset Aktif</h3>
                <a href="{{ route('pengelola.keluhan.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($keluhanBaru as $k)
                    <a href="{{ route('pengelola.keluhan.show', $k) }}"
                       class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition group">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-amber-600 transition truncate">{{ $k->judul }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $k->user->name ?? '-' }} · {{ $k->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="ml-3 px-2.5 py-1 text-[10px] font-bold {{ $k->status === 'pending' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} rounded-full flex-shrink-0 capitalize">
                            {{ ucfirst($k->status) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Tidak ada laporan keluhan aktif saat ini</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

