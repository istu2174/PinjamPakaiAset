<x-app-layout>
    <x-slot name="title">Dashboard Administrator</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Administrator</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Sistem Informasi Peminjaman Aset Publik Dispora Kota Palembang</p>
        </div>
    </div>

    @if(isset($stats['peminjam_pending']) && $stats['peminjam_pending'] > 0)
        <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-amber-500/20 flex-shrink-0">
                    ⏳
                </div>
                <div>
                    <h3 class="text-xs font-black text-amber-900 uppercase tracking-wide">Pemberitahuan: Akun Peminjam Baru Menunggu Verifikasi</h3>
                    <p class="text-xs text-amber-800 mt-0.5">Ada <strong>{{ $stats['peminjam_pending'] }} user baru</strong> mendaftar yang belum diverifikasi oleh Admin.</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index', ['status' => 'menunggu_verifikasi']) }}"
               class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition shadow-sm whitespace-nowrap active:scale-[0.99]">
                Verifikasi Akun ({{ $stats['peminjam_pending'] }}) →
            </a>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Peminjam</span>
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total_peminjam'] }}</p>
            <p class="text-[11px] font-bold text-emerald-600 mt-1">User Peminjam Aktif</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Aset Publik</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total_aset'] }}</p>
            <p class="text-[11px] font-medium text-slate-400 mt-1">{{ $stats['aset_tersedia'] }} Siap Dipinjam</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengajuan Baru</span>
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['peminjaman_pending'] }}</p>
            <p class="text-[11px] font-bold text-amber-600 mt-1">Perlu Verifikasi / Persetujuan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Keluhan Aktif</span>
                <div class="w-9 h-9 rounded-xl bg-rose-500/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['keluhan_pending'] }}</p>
            <p class="text-[11px] font-bold text-rose-600 mt-1">Belum Selesai</p>
        </div>
    </div>

    {{-- Row 2: Stats detail --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $statCards2 = [
                ['label' => 'Aset Dipinjam',   'value' => $stats['aset_dipinjam'],   'color' => 'blue',   'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                ['label' => 'Aset Maintenance', 'value' => $stats['aset_maintenance'], 'color' => 'orange', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                ['label' => 'Sedang Dipinjam', 'value' => $stats['peminjaman_aktif'], 'color' => 'purple', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Peminjaman Bulan Ini (Rekap)', 'value' => $stats['peminjaman_bulan_ini'], 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-500/10',   'text' => 'text-blue-600'],
                'orange' => ['bg' => 'bg-amber-500/10',  'text' => 'text-amber-600'],
                'purple' => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600'],
                'green'  => ['bg' => 'bg-emerald-500/10','text' => 'text-emerald-600'],
            ];
        @endphp
        @foreach($statCards2 as $card)
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $colorMap[$card['color']]['bg'] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 {{ $colorMap[$card['color']]['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-extrabold text-slate-800 tracking-tight">{{ $card['value'] }}</p>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ $card['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pengajuan Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Pengajuan Peminjaman Terbaru</h3>
                <a href="{{ route('admin.peminjaman.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pengajuanTerbaru as $p)
                    <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 transition group">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition truncate">{{ $p->peminjam->name ?? '-' }}</p>
                            <p class="text-[11px] font-medium text-slate-400 truncate mt-0.5">{{ $p->aset->nama_aset ?? '-' }} · {{ $p->created_at->diffForHumans() }}</p>
                        </div>
                        @php
                            $statusBadge = match($p->status) {
                                'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default        => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="ml-3 px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $statusBadge }} flex-shrink-0 capitalize">
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Tidak ada pengajuan terbaru</div>
                @endforelse
            </div>
        </div>

        {{-- Keluhan Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Keluhan Aset Terbaru</h3>
                <a href="{{ route('admin.peminjaman.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($keluhanTerbaru as $k)
                    <div class="px-5 py-3.5 hover:bg-slate-50 transition group">
                        <p class="text-xs font-bold text-slate-800 group-hover:text-amber-600 transition truncate">{{ $k->judul }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $k->user->name ?? '-' }} · {{ $k->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Tidak ada keluhan yang belum ditangani</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Algorithm Info Box --}}
    <div class="mt-6 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-5 shadow-md">
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-blue-400">Integrasi Algoritma Cerdas</p>
                <p class="text-xs font-medium text-slate-300 mt-1 leading-relaxed">
                    <strong>Decision Tree C4.5 / ID3</strong> digunakan untuk menentukan klasifikasi kelaikan fisik aset (Layak Pakai, Perlu Maintenance, atau Layak Lelang).
                    <strong>Priority Scheduling</strong> mengurutkan antrean permohonan peminjaman aset berdasarkan tingkat instansi (Dinas Dispora = 3, Organisasi = 2, Umum = 1).
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

