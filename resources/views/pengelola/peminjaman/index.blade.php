<x-app-layout>
    <x-slot name="title">Pengajuan Peminjaman</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Verifikasi Pengajuan Pinjaman</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Evaluasi kelayakan aset (Decision Tree) & prioritas antrean (Priority Scheduling)</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <select name="status" class="w-full text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                    <option value="">Semua Status Pengajuan</option>
                    @foreach(['pending'=>'Pending Verifikasi','diverifikasi'=>'Diverifikasi','disetujui'=>'Disetujui','dipinjam'=>'Sedang Dipinjam','selesai'=>'Selesai','ditolak'=>'Ditolak'] as $s => $label)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                Filter Data
            </button>
            <a href="{{ route('pengelola.peminjaman.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-all">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">No. Pengajuan</th>
                        <th class="px-5 py-3.5">Peminjam</th>
                        <th class="px-5 py-3.5">Aset Dipinjam</th>
                        <th class="px-5 py-3.5">Periode Pinjam</th>
                        <th class="px-5 py-3.5">Prioritas</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($peminjamans as $p)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-slate-500">{{ $p->nomor_pengajuan }}</td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800">{{ $p->peminjam->name ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold capitalize mt-0.5">{{ $p->peminjam->jenis_peminjam ?? '' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $p->aset->nama_aset ?? '-' }}</p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 rounded-md">
                                    📦 {{ $p->jumlah_pinjam ?? 1 }} Unit
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-500">
                                <span class="font-semibold text-slate-700">{{ $p->tanggal_pinjam->format('d/m/Y') }}</span>
                                <span class="text-slate-400">s/d</span>
                                <span class="font-semibold text-slate-700">{{ $p->tanggal_kembali->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @php $sc = $p->priority_score; @endphp
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full border
                                    {{ $sc==3?'bg-amber-50 text-amber-700 border-amber-200':($sc==2?'bg-blue-50 text-blue-700 border-blue-200':'bg-slate-100 text-slate-600 border-slate-200') }}">
                                    {{ $sc==3?'★ Prioritas 3 (Tinggi)':($sc==2?'◆ Prioritas 2 (Sedang)':'● Prioritas 1 (Normal)') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusBadge = match($p->status) {
                                        'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'ditolak'      => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default        => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $statusBadge }} capitalize">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('pengelola.peminjaman.show', $p) }}"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $p->status === 'pending' ? 'Verifikasi Sekarang →' : 'Detail →' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-xs font-medium text-slate-400">Belum ada pengajuan peminjaman ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjamans->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $peminjamans->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>

