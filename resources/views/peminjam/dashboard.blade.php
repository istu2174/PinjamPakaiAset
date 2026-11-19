<x-app-layout>
    <x-slot name="title">Dashboard Peminjam</x-slot>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Halo, {{ Str::words(Auth::user()->name, 1, '') }}! 👋</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Selamat datang di portal SIPANDA Dispora Kota Palembang</p>
        </div>
        <a href="{{ route('peminjam.cari') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 active:scale-[0.99]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari & Pinjam Aset
        </a>
    </div>

    {{-- Priority Badge Widget --}}
    @php
        $ps = Auth::user()->priority_score;
        $badgeBg = $ps==3
            ? 'bg-amber-500/10 border-amber-500/30 text-amber-900'
            : ($ps==2 ? 'bg-blue-500/10 border-blue-500/30 text-blue-900' : 'bg-slate-500/10 border-slate-500/30 text-slate-900');
        $label = $ps==3 ? 'Peminjam Prioritas Tinggi (Pemerintah)' : ($ps==2 ? 'Peminjam Prioritas Sedang (Organisasi)' : 'Peminjam Umum / Masyarakat');
        $icon = $ps==3 ? '⭐' : ($ps==2 ? '💎' : '👤');
    @endphp
    <div class="mb-6 p-4 rounded-2xl border {{ $badgeBg }} backdrop-blur-sm transition-all shadow-sm">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-white/80 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                {{ $icon }}
            </div>
            <div>
                <p class="font-bold text-xs uppercase tracking-wider text-slate-800">{{ $label }}</p>
                <p class="text-xs text-slate-600 font-medium mt-0.5">
                    Skor Prioritas: <strong class="font-bold text-blue-700">{{ $ps }}/3</strong> · Kategori Instansi: <span class="capitalize font-semibold">{{ Auth::user()->jenis_peminjam ?? 'Umum' }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $statItems = [
                ['label'=>'Total Pengajuan','value'=>$stats['total_pengajuan'],'bg'=>'bg-blue-500/10 text-blue-600','border'=>'border-blue-200/60'],
                ['label'=>'Sedang Dipinjam','value'=>$stats['sedang_dipinjam'],'bg'=>'bg-indigo-500/10 text-indigo-600','border'=>'border-indigo-200/60'],
                ['label'=>'Menunggu Proses','value'=>$stats['menunggu_proses'],'bg'=>'bg-amber-500/10 text-amber-600','border'=>'border-amber-200/60'],
                ['label'=>'Selesai','value'=>$stats['selesai'],'bg'=>'bg-emerald-500/10 text-emerald-600','border'=>'border-emerald-200/60'],
            ];
        @endphp
        @foreach($statItems as $s)
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $s['label'] }}</span>
                    <span class="w-2 h-2 rounded-full {{ $s['bg'] }}"></span>
                </div>
                <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $s['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Peminjaman Aktif --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Peminjaman Aktif</h3>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($peminjamanAktif as $p)
                    <a href="{{ route('peminjam.peminjaman.show', $p) }}"
                       class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition group">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-600 transition">{{ $p->aset->nama_aset ?? '-' }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">Jadwal Kembali: {{ $p->tanggal_kembali->format('d M Y') }}</p>
                        </div>
                        @php
                            $statusBadge = match($p->status) {
                                'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                default        => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="ml-3 px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $statusBadge }} flex-shrink-0 capitalize">
                            {{ ucfirst($p->status) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-xs text-slate-400">
                        <p class="font-medium">Belum ada peminjaman aktif saat ini</p>
                        <a href="{{ route('peminjam.cari') }}" class="text-blue-600 font-bold hover:underline text-xs mt-1.5 inline-block">Cari & Pinjam Aset →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Notifikasi Terbaru</h3>
                <a href="{{ route('notifikasi.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($notifikasiTerbaru as $n)
                    <div class="px-5 py-3.5 {{ !$n->dibaca ? 'bg-blue-50/40' : '' }} transition">
                        <p class="text-xs font-bold text-slate-800">{{ $n->judul }}</p>
                        <p class="text-[11px] font-medium text-slate-500 truncate mt-0.5">{{ Str::limit($n->pesan, 65) }}</p>
                        <p class="text-[10px] font-medium text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-xs text-slate-400 font-medium">Belum ada notifikasi baru</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

