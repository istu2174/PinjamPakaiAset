<x-app-layout>
    <x-slot name="title">Riwayat Pinjaman</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Riwayat Pinjaman Aset</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Daftar seluruh pengajuan peminjaman aset yang pernah Anda buat</p>
        </div>
        <a href="{{ route('peminjam.peminjaman.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 active:scale-[0.99]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Pinjaman Baru
        </a>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[180px]">
                <select name="status" class="w-full text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                    <option value="">Semua Status Peminjaman</option>
                    @foreach(['pending'=>'Menunggu Verifikasi','diverifikasi'=>'Diverifikasi','disetujui'=>'Disetujui','dipinjam'=>'Sedang Dipinjam','selesai'=>'Selesai','ditolak'=>'Ditolak','dibatalkan'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                Filter
            </button>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-all">
                Reset
            </a>
        </form>
    </div>

    {{-- Cards List --}}
    <div class="space-y-3.5">
        @forelse($peminjamans as $p)
            <a href="{{ route('peminjam.peminjaman.show', $p) }}"
               class="block bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 hover:border-blue-500/50 hover:shadow-md transition-all duration-200 group">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider font-mono">{{ $p->nomor_pengajuan }}</span>
                        <h3 class="text-sm font-extrabold text-slate-800 group-hover:text-blue-600 transition-colors mt-0.5">{{ $p->aset->nama_aset ?? '-' }}</h3>
                    </div>
                    @php
                        $statusClass = match($p->status) {
                            'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                            'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                            'ditolak', 'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default        => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="px-3 py-1 text-[11px] font-bold rounded-full border {{ $statusClass }} flex-shrink-0 capitalize">
                        {{ ucfirst($p->status) }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-4 mt-4 pt-3 border-t border-slate-100 text-xs font-medium text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ $p->tanggal_pinjam->format('d M Y') }} – {{ $p->tanggal_kembali->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg border border-blue-100 font-bold text-[11px]">
                        <span>📦 {{ $p->jumlah_pinjam ?? 1 }} Unit</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-amber-500 font-bold">⚡ Prioritas:</span>
                        <span class="font-bold text-slate-700">{{ $p->priority_score }}/3</span>
                    </div>
                    <div class="ml-auto text-[11px] text-slate-400">
                        {{ $p->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-200/80">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum ada pengajuan pinjaman</h3>
                <p class="text-slate-400 text-xs font-medium mt-1 mb-4">Mulai cari dan ajukan aset yang Anda butuhkan</p>
                <a href="{{ route('peminjam.cari') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    Cari Aset Sekarang →
                </a>
            </div>
        @endforelse
    </div>

    @if($peminjamans->hasPages())
        <div class="mt-6">{{ $peminjamans->withQueryString()->links() }}</div>
    @endif
</x-app-layout>

