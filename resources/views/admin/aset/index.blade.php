<x-app-layout>
    <x-slot name="title">Manajemen Aset</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Master Data Aset & Inventaris</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Kelola seluruh aset, peralatan, dan fasilitas publik Dispora Kota Palembang</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.aset.kategori.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-100 transition-all">
                Kategori Aset
            </a>
            <a href="{{ route('admin.aset.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Aset Baru
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @php
            $statColors = ['bg-emerald-50 text-emerald-700 border-emerald-200','bg-indigo-50 text-indigo-700 border-indigo-200','bg-amber-50 text-amber-700 border-amber-200','bg-rose-50 text-rose-700 border-rose-200'];
            $statLabels = ['Tersedia','Dipinjam','Maintenance','Tidak Layak / Auction'];
            $statVals   = [
                \App\Models\Aset::where('status','tersedia')->count(),
                \App\Models\Aset::where('status','dipinjam')->count(),
                \App\Models\Aset::where('status','maintenance')->count(),
                \App\Models\Aset::where('kondisi','layak_lelang')->count(),
            ];
        @endphp
        @foreach(range(0,3) as $i)
            <div class="bg-white rounded-2xl border {{ $statColors[$i] }} p-4 text-center shadow-sm">
                <p class="text-2xl font-black tracking-tight">{{ $statVals[$i] }}</p>
                <p class="text-[11px] font-extrabold uppercase tracking-wider mt-0.5">{{ $statLabels[$i] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="bisa_dipinjam" class="text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                <option value="">Semua Hak Akses</option>
                <option value="1" {{ request('bisa_dipinjam') === '1' ? 'selected' : '' }}>✓ Bisa Dipinjam Publik</option>
                <option value="0" {{ request('bisa_dipinjam') === '0' ? 'selected' : '' }}>🔒 Internal Dispora (Non-Pinjam)</option>
            </select>
            <select name="status" class="text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                <option value="">Semua Status Aset</option>
                <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <select name="kategori_id" class="text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama aset, merk, kode..."
                   class="text-xs font-medium border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 flex-1 min-w-[200px] bg-slate-50/50 focus:bg-white">
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">Filter</button>
            <a href="{{ route('admin.aset.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-all">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Nama Aset</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Kode Aset</th>
                        <th class="px-5 py-3.5">Stok Unit (Semua)</th>
                        <th class="px-5 py-3.5">Akses Pinjam</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($asets as $i => $aset)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-slate-400">{{ $asets->firstItem() + $i }}</td>
                            <td class="px-5 py-4">
                                <p class="font-extrabold text-slate-800 text-sm">{{ $aset->nama_aset }}</p>
                                <p class="text-slate-400 text-[11px] font-medium mt-0.5">{{ $aset->merk ?? '' }} {{ $aset->tahun_pengadaan ? '('.$aset->tahun_pengadaan.')' : '' }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-600">{{ $aset->kategori->nama ?? '-' }}</td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-500">{{ $aset->kode_aset }}</td>
                            <td class="px-5 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                        <span class="text-xs">{{ $aset->jumlah }} unit</span>
                                        <span class="text-[10px] text-slate-400 font-normal">(Total)</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[11px]">
                                        <span class="text-indigo-600 font-semibold">{{ $aset->stok_dipinjam }} dipinjam</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="font-bold {{ $aset->stok_tersedia > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $aset->stok_tersedia }} tersedia
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if($aset->bisa_dipinjam)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border bg-emerald-50 text-emerald-700 border-emerald-200">
                                        ✓ Bisa Dipinjam
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border bg-slate-100 text-slate-600 border-slate-300">
                                        🔒 Internal Dispora
                                    </span>
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
                                <div class="flex items-center justify-end gap-2.5 font-bold">
                                    <a href="{{ route('admin.aset.show', $aset) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Detail</a>
                                    <a href="{{ route('admin.aset.edit', $aset) }}" class="text-slate-600 hover:text-slate-800 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.aset.destroy', $aset) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-xs font-medium text-slate-400">Belum ada aset terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asets->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $asets->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>

