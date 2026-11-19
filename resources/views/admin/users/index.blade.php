<x-app-layout>
    <x-slot name="title">Manajemen User</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Akun Pengguna</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Kelola hak akses role (Admin, Pengelola, Peminjam) & klasifikasi priority score</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laporan.peminjam.excel') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-xl hover:bg-emerald-100 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah User Baru
            </a>
        </div>
    </div>

    @if(isset($pendingCount) && $pendingCount > 0)
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <h4 class="text-xs font-black text-amber-900 uppercase tracking-wide">Pemberitahuan Verifikasi Peminjam Baru</h4>
                    <p class="text-xs text-amber-800 mt-0.5">Terdapat <strong>{{ $pendingCount }} akun peminjam baru</strong> yang sedang menunggu validasi & verifikasi dari Admin.</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index', ['status' => 'menunggu_verifikasi']) }}"
               class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition shadow-sm whitespace-nowrap">
                Lihat Akun Menunggu ({{ $pendingCount }})
            </a>
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="role" class="text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pengelola" {{ request('role') === 'pengelola' ? 'selected' : '' }}>Pengelola</option>
                <option value="peminjam" {{ request('role') === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
            </select>
            <select name="status" class="text-xs font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 bg-slate-50/50">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>⏳ Menunggu Verifikasi</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="dikecualikan_rekap" {{ request('status') === 'dikecualikan_rekap' ? 'selected' : '' }}>🚫 Dikecualikan dari Rekap</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, NIK..."
                   class="text-xs font-medium border border-slate-200 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 flex-1 min-w-[200px] bg-slate-50/50 focus:bg-white">
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition-all">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-5 py-3.5">NIK</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Instansi</th>
                        <th class="px-5 py-3.5">Prioritas</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($users as $i => $user)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono text-slate-400 font-bold">{{ $users->firstItem() + $i }}</td>
                            <td class="px-5 py-4">
                                <p class="font-extrabold text-slate-800 text-sm">{{ $user->name }}</p>
                                <p class="text-slate-400 text-[11px] font-medium">{{ $user->email }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-500">{{ $user->nik ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $roleBadge = match($user->role) {
                                        'admin'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'pengelola' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'peminjam'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default     => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $roleBadge }} capitalize">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 capitalize font-semibold text-slate-600">{{ $user->jenis_peminjam ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @php $sc = $user->priority_score; @endphp
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full border
                                    {{ $sc == 3 ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $sc == 2 ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $sc == 1 ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}">
                                    {{ $sc == 3 ? '★ Prioritas 3 (Tinggi)' : ($sc == 2 ? '◆ Prioritas 2 (Sedang)' : '● Prioritas 1 (Normal)') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if(!$user->is_active && $user->role === 'peminjam')
                                    <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full border bg-amber-50 text-amber-700 border-amber-300">
                                        ⏳ Menunggu Verifikasi
                                    </span>
                                @elseif($user->is_active)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border bg-emerald-50 text-emerald-700 border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border bg-slate-100 text-slate-500 border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                                @if($user->role === 'peminjam' && $user->dikecualikan_rekap)
                                    <span class="inline-block mt-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full border bg-orange-50 text-orange-700 border-orange-200 whitespace-nowrap"
                                          title="{{ $user->catatan_pengecualian ?: 'Aktivitas akun ini tidak dihitung dalam rekap bulanan' }}">
                                        🚫 Tidak Direkap
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2.5 font-bold">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Detail</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-slate-600 hover:text-slate-800 hover:underline">Edit</a>
                                    @if(!$user->is_active)
                                        <form method="POST" action="{{ route('admin.users.toggle-aktif', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold shadow-sm transition">
                                                ✓ Verifikasi
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.toggle-aktif', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 hover:underline">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-12 text-center text-xs font-medium text-slate-400">Tidak ada akun user ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $users->withQueryString()->links() }}</div>
        @endif
    </div>
</x-app-layout>

