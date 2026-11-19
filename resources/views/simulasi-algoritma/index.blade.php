<x-app-layout>
    <x-slot name="title">Lab Simulasi & Komparasi Algoritma</x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-800 relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/20 border border-blue-400/30 rounded-full text-blue-300 text-[11px] font-extrabold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    Fitur Evaluasi Akademik & Sidang Skripsi
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Lab Simulasi Komparasi Algoritma
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed">
                    Uji dan buktikan perbedaan nyata antara sistem <strong class="text-white">Tanpa Algoritma (FIFO Konvensional)</strong> vs <strong class="text-blue-300">Dengan Algoritma (Priority Scheduling)</strong> dalam menyelesaikan konflik bentrok jadwal peminjaman aset sarpras Dispora.
                </p>
            </div>
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        {{-- Skenario Selector Tabs --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Pilih Kasus Uji Skenario Sidang:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($daftarKasus as $key => $item)
                    <a href="{{ route('simulasi.algoritma', ['skenario' => $key]) }}"
                       class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between {{ $skenario == $key ? 'bg-blue-50/80 border-blue-500/40 shadow-sm ring-2 ring-blue-500/20' : 'bg-slate-50/60 border-slate-200/70 hover:bg-slate-100' }}">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md {{ $skenario == $key ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                    Kasus #{{ $key }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400">
                                    Kapasitas: {{ $item['kapasitas'] }} Unit
                                </span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900 leading-snug">{{ $item['judul'] }}</h3>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium mt-2 line-clamp-2 leading-relaxed">
                            {{ $item['deskripsi'] }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Active Scenario Banner --}}
        <div class="bg-gradient-to-r from-blue-50 via-indigo-50/50 to-white border border-blue-200/80 rounded-2xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-100/80 px-2.5 py-1 rounded-lg">
                    Skenario Sedang Berjalan
                </span>
                <h2 class="text-base font-extrabold text-slate-900 mt-1.5">{{ $kasusAktif['judul'] }}</h2>
                <p class="text-xs text-slate-600 font-medium mt-0.5 max-w-3xl leading-relaxed">{{ $kasusAktif['deskripsi'] }}</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="bg-white px-4 py-2.5 rounded-xl border border-blue-200 shadow-sm text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Kapasitas Aset</p>
                    <p class="text-lg font-black text-blue-700">{{ $hasil['kapasitas_aset'] }} Unit</p>
                </div>
            </div>
        </div>

        {{-- Side-by-Side Comparison Columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Column 1: Tanpa Algoritma (FIFO Konvensional) --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center font-black text-sm">
                                ✕
                            </span>
                            <div>
                                <h3 class="font-black text-slate-900 text-sm">Tanpa Algoritma (Konvensional)</h3>
                                <p class="text-[11px] font-semibold text-rose-600">First-Come First-Served (FIFO Murni)</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-extrabold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                            Metode Lama
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 font-medium mb-4 leading-relaxed">
                        Sistem hanya melihat <strong>urutan waktu pendaftaran (created_at)</strong> tanpa memperhitungkan kedudukan instansi atau tingkat urgensi kegiatan.
                    </p>

                    <div class="space-y-3">
                        @foreach($hasil['tanpa_algoritma'] as $index => $item)
                            @php
                                $isLolos = str_starts_with($item['status_keputusan'], 'Diterima');
                            @endphp
                            <div class="p-4 rounded-2xl border transition-all {{ $isLolos ? 'bg-emerald-50/50 border-emerald-200/80' : 'bg-rose-50/50 border-rose-200/80' }}">
                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded {{ $item['jenis_peminjam'] == 'pemerintah' ? 'bg-amber-100 text-amber-800' : ($item['jenis_peminjam'] == 'organisasi' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                                {{ ucfirst($item['jenis_peminjam']) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-900">{{ $item['nama_peminjam'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-mono mt-1">
                                            ⏱ Waktu Daftar: {{ \Carbon\Carbon::parse($item['waktu_pengajuan'])->format('H:i:s') }} WIB · Pinjam: {{ $item['jumlah_pinjam'] }} Unit
                                        </p>
                                    </div>
                                    <span class="text-[11px] font-black px-2.5 py-1 rounded-lg border {{ $isLolos ? 'bg-emerald-600 text-white border-emerald-700' : 'bg-rose-600 text-white border-rose-700' }}">
                                        {{ $item['status_keputusan'] }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-medium {{ $isLolos ? 'text-emerald-800' : 'text-rose-800' }} mt-2 pt-2 border-t {{ $isLolos ? 'border-emerald-200/50' : 'border-rose-200/50' }}">
                                    💬 {{ $item['alasan'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 p-4 rounded-2xl bg-rose-50/60 border border-rose-200/60 text-xs text-rose-900">
                    <p class="font-extrabold uppercase tracking-wider text-[10px] mb-1">⚠ Kelemahan Fatal Konvensional:</p>
                    <p class="leading-relaxed">
                        Jika instansi dinas/pemerintah memiliki agenda dinas resmi mendesak namun terlambat mendaftar beberapa menit, instansi dinas tersebut <strong>langsung ditolak</strong>.
                    </p>
                </div>
            </div>

            {{-- Column 2: Dengan Algoritma (Priority Scheduling) --}}
            <div class="bg-white rounded-3xl shadow-sm border border-blue-300 p-6 flex flex-col justify-between ring-2 ring-blue-500/10">
                <div>
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-blue-500/30">
                                ⚡
                            </span>
                            <div>
                                <h3 class="font-black text-slate-900 text-sm">Dengan Algoritma Priority Scheduling</h3>
                                <p class="text-[11px] font-semibold text-blue-600">Bobot Prioritas + Tie-Breaker FCFS</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg">
                            Algoritma Cerdas ✓
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 font-medium mb-4 leading-relaxed">
                        Sistem menghitung <strong>skor bobot permohonan</strong>: Pemerintah (3) > Organisasi (2) > Umum (1). FCFS hanya digunakan jika skor sama.
                    </p>

                    <div class="space-y-3">
                        @foreach($hasil['dengan_algoritma'] as $index => $item)
                            @php
                                $isLolos = str_starts_with($item['status_keputusan'], 'Diterima');
                            @endphp
                            <div class="p-4 rounded-2xl border transition-all {{ $isLolos ? 'bg-blue-50/50 border-blue-200/80 shadow-sm' : 'bg-slate-50 border-slate-200/80 opacity-75' }}">
                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded {{ $item['priority_score'] == 3 ? 'bg-amber-500 text-white' : ($item['priority_score'] == 2 ? 'bg-blue-600 text-white' : 'bg-slate-500 text-white') }}">
                                                Skor {{ $item['priority_score'] }} · {{ ucfirst($item['jenis_peminjam']) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-900">{{ $item['nama_peminjam'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-mono mt-1">
                                            ⏱ Waktu Daftar: {{ \Carbon\Carbon::parse($item['waktu_pengajuan'])->format('H:i:s') }} WIB · Pinjam: {{ $item['jumlah_pinjam'] }} Unit
                                        </p>
                                    </div>
                                    <span class="text-[11px] font-black px-2.5 py-1 rounded-lg border {{ $isLolos ? 'bg-blue-600 text-white border-blue-700' : 'bg-slate-400 text-white border-slate-500' }}">
                                        {{ $item['status_keputusan'] }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-medium {{ $isLolos ? 'text-blue-900 font-semibold' : 'text-slate-600' }} mt-2 pt-2 border-t {{ $isLolos ? 'border-blue-200/50' : 'border-slate-200/50' }}">
                                    💬 {{ $item['alasan'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 p-4 rounded-2xl bg-blue-50/80 border border-blue-200 text-xs text-blue-950">
                    <p class="font-extrabold uppercase tracking-wider text-[10px] text-blue-700 mb-1">✓ Keunggulan Priority Scheduling:</p>
                    <p class="leading-relaxed">
                        Sarpras negara diprioritaskan secara transparan untuk fungsi kedinasan & kegiatan pemuda yang mendesak, sekaligus menghindari keluhan manual antar instansi.
                    </p>
                </div>
            </div>
        </div>

        {{-- Key Differences Highlight Box --}}
        @if($hasil['ada_perbedaan'])
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-amber-300 bg-gradient-to-br from-amber-50/40 via-white to-amber-50/20">
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-7 h-7 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm">
                        ★
                    </span>
                    <h3 class="text-sm font-extrabold text-slate-900">Perbedaan Kunci yang Membuktikan Manfaat Algoritma:</h3>
                </div>
                <div class="space-y-3">
                    @foreach($hasil['perbedaan'] as $diff)
                        <div class="p-3.5 bg-white rounded-xl border border-amber-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex-1">
                                <p class="font-extrabold text-slate-900">{{ $diff['nama_peminjam'] }}</p>
                                <p class="text-slate-500 mt-0.5 leading-relaxed">{{ $diff['kesimpulan'] }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Tanpa: {{ $diff['tanpa_algoritma'] }}
                                </span>
                                <span class="text-slate-400 font-bold">→</span>
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Algoritma: {{ $diff['dengan_algoritma'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
