<x-app-layout>
    <x-slot name="title">Laporan & Export</x-slot>
    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pusat Laporan & Export Data</h2>
        <p class="text-xs font-medium text-slate-500 mt-1">Unduh rekapitulasi data aset, peminjaman, dan biodata pengguna dalam format PDF atau Excel</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Laporan Peminjaman PDF --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col justify-between group hover:border-rose-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center mb-4 text-rose-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-extrabold text-slate-900 text-sm mb-1">Rekap Bulanan Peminjaman Aset</h3>
                <p class="text-xs text-slate-400 font-medium mb-4 leading-relaxed">Rekap transaksi peminjaman & pengembalian per bulan dalam format PDF resmi. Akun yang dikecualikan admin tidak dihitung.</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.peminjaman.pdf') }}" target="_blank" class="space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <select name="bulan" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium bg-slate-50/50 focus:border-rose-500 outline-none">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" @selected($b == now()->month)>
                                {{ \Carbon\Carbon::create(null, $b, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="tahun" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium bg-slate-50/50 focus:border-rose-500 outline-none">
                        <option value="">Semua Tahun</option>
                        @foreach(range(now()->year, now()->year - 4) as $t)
                            <option value="{{ $t }}" @selected($t == now()->year)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium bg-slate-50/50 focus:border-rose-500 outline-none">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Pending','disetujui'=>'Disetujui','dipinjam'=>'Dipinjam','selesai'=>'Selesai (Dikembalikan)','ditolak'=>'Ditolak','dibatalkan'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-rose-500/20 active:scale-[0.99]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download PDF →
                </button>
            </form>
        </div>

        {{-- Laporan Aset PDF --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col justify-between group hover:border-amber-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-4 text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="font-extrabold text-slate-900 text-sm mb-1">Laporan Inventaris & Kondisi Aset</h3>
                <p class="text-xs text-slate-400 font-medium mb-6 leading-relaxed">Rekap seluruh kondisi fisik & kelayakan aset berdasarkan hasil evaluasi Algoritma Decision Tree C4.5.</p>
            </div>
            <a href="{{ route('admin.laporan.aset.pdf') }}" target="_blank"
               class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-amber-500/20 active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF →
            </a>
        </div>

        {{-- Data Peminjam Excel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col justify-between group hover:border-emerald-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-4 text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-extrabold text-slate-900 text-sm mb-1">Data Peminjam & Priority Score</h3>
                <p class="text-xs text-slate-400 font-medium mb-6 leading-relaxed">Export seluruh database akun pengguna, NIK, jenis instansi, serta kalkulasi skor Priority Scheduling ke format Excel.</p>
            </div>
            <a href="{{ route('admin.laporan.peminjam.excel') }}"
               class="w-full inline-flex items-center justify-center gap-2 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Excel →
            </a>
        </div>
    </div>

    {{-- Akun yang dikecualikan dari rekap --}}
    <div class="mt-6 bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
            <div>
                <h3 class="font-bold text-amber-900 text-xs uppercase tracking-wider">Akun Dikecualikan dari Rekap Bulanan</h3>
                <p class="text-xs text-amber-800/80 font-medium mt-0.5">Aktivitas peminjaman & pengembalian akun berikut tetap berjalan normal, tetapi tidak dihitung dalam rekap.</p>
            </div>
            <a href="{{ route('admin.users.index', ['status' => 'dikecualikan_rekap']) }}" class="text-xs font-bold text-amber-700 hover:underline flex-shrink-0">Kelola Akun →</a>
        </div>
        @if($akunDikecualikan->isEmpty())
            <p class="text-xs text-amber-700 font-medium">Belum ada akun yang dikecualikan.</p>
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($akunDikecualikan as $u)
                    <a href="{{ route('admin.users.show', $u) }}" class="px-3 py-1.5 bg-white border border-amber-200 rounded-xl text-xs font-semibold text-amber-900 hover:bg-amber-100 transition">
                        {{ $u->name }} <span class="text-amber-600/70 font-medium">· {{ $u->email }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

