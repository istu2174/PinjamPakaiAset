<x-app-layout>
    <x-slot name="title">Keluhan Aset</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen & Respon Keluhan</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Daftar laporan keluhan dari peminjam aset publik</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <select name="status" class="w-full text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                    <option value="">Semua Status Keluhan</option>
                    <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending / Baru</option>
                    <option value="diproses" {{ request('status')==='diproses'?'selected':'' }}>Sedang Diprosess</option>
                    <option value="selesai" {{ request('status')==='selesai'?'selected':'' }}>Selesai Diselesaikan</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                Filter
            </button>
            <a href="{{ route('pengelola.keluhan.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-all">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">Judul Laporan</th>
                        <th class="px-5 py-3.5">Pelapor</th>
                        <th class="px-5 py-3.5">Tanggal Lapor</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($keluhanss as $k)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800 text-sm">{{ $k->judul }}</p>
                                <p class="text-[11px] text-slate-400 font-medium truncate max-w-xs mt-0.5">{{ Str::limit($k->isi, 65) }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-700">{{ $k->user->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-400 text-[11px] font-medium">{{ $k->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $badgeClass = match($k->status) {
                                        'pending'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'diproses' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'selesai'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default    => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $badgeClass }} capitalize">
                                    {{ ucfirst($k->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('pengelola.keluhan.show', $k) }}"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $k->status === 'pending' ? 'Tanggapi Laporan →' : 'Detail →' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-xs font-medium text-slate-400">Tidak ada laporan keluhan ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($keluhanss->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $keluhanss->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>

