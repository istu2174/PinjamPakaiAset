<x-app-layout>
    <x-slot name="title">Detail Peminjaman</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Manajemen Peminjaman
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan Peminjaman</h2>
            <p class="text-xs font-mono font-bold text-slate-400 mt-0.5">{{ $peminjaman->nomor_pengajuan }}</p>
        </div>
        @if(in_array($peminjaman->status, ['pending', 'diverifikasi']))
            <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500">
                ℹ Persetujuan & penolakan peminjaman dilakukan oleh Pengelola Aset
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Info Peminjaman --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Detail & Keperluan Peminjaman</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pemohon / Peminjam</p>
                        <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $peminjaman->peminjam->name ?? '-' }}</p>
                        <p class="text-xs font-medium text-slate-500 capitalize mt-0.5">{{ $peminjaman->peminjam->jenis_peminjam ?? '' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aset Publik Target</p>
                        <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $peminjaman->aset->nama_aset ?? '-' }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $peminjaman->aset->kategori->nama ?? '' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Mulai Pinjam</p>
                        <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengembalian</p>
                        <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-blue-950 uppercase tracking-wider">Jumlah Barang yang Dipinjam</p>
                            <p class="text-xs text-blue-700 font-medium mt-0.5">Sisa stok aset fisik saat ini: {{ $peminjaman->aset->stok_tersedia }} unit</p>
                        </div>
                        <span class="text-sm font-black text-blue-800 bg-white px-3.5 py-1.5 rounded-xl border border-blue-200 shadow-sm">
                            {{ $peminjaman->jumlah_pinjam ?? 1 }} Unit
                        </span>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Maksud / Keperluan Peminjaman</p>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $peminjaman->keperluan }}</p>
                    </div>
                </div>
            </div>

            {{-- Catatan Pengelola --}}
            @if($peminjaman->catatan_pengelola)
                <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-5 text-xs">
                    <p class="font-bold text-blue-900 uppercase tracking-wider mb-1">Catatan Hasil Verifikasi Pengelola</p>
                    <p class="text-blue-800 font-medium leading-relaxed">{{ $peminjaman->catatan_pengelola }}</p>
                </div>
            @endif

            @if($peminjaman->alasan_penolakan)
                <div class="bg-rose-50/70 border border-rose-200/80 rounded-2xl p-5 text-xs">
                    <p class="font-bold text-rose-900 uppercase tracking-wider mb-1">Catatan / Alasan Penolakan</p>
                    <p class="text-rose-800 font-medium leading-relaxed">{{ $peminjaman->alasan_penolakan }}</p>
                </div>
            @endif

            {{-- Surat --}}
            @if($peminjaman->surat)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-0.5">Surat Pernyataan Peminjaman Barcode</h3>
                        <p class="text-xs text-slate-400 font-medium">Dokumen telah disahkan secara digital dengan QR Code</p>
                    </div>
                    <a href="{{ route('surat.pdf', $peminjaman) }}" target="_blank"
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl border border-blue-200 transition-all">
                        Download Document PDF →
                    </a>
                </div>
            @endif
        </div>

        {{-- Right: Status & Priority --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">Status & Priority Scheduling</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pengajuan</p>
                        @php
                            $statusBadge = match($peminjaman->status) {
                                'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                'ditolak'      => 'bg-rose-50 text-rose-700 border-rose-200',
                                'dibatalkan'   => 'bg-slate-100 text-slate-400 border-slate-200',
                                default        => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="inline-block px-3.5 py-1.5 text-xs font-extrabold rounded-full border {{ $statusBadge }} capitalize">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Skor Prioritas Sistem</p>
                        <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100 text-center">
                            <span class="text-3xl font-black text-slate-800 tracking-tight">{{ $peminjaman->priority_score }}</span>
                            <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mt-1">
                                {{ $peminjaman->priority_score == 3 ? '★ Priority 3 (Tinggi - Pemerintah)' : ($peminjaman->priority_score == 2 ? '◆ Priority 2 (Sedang - Organisasi)' : '● Priority 1 (Normal - Umum)') }}
                            </p>
                        </div>
                    </div>
                    @if($peminjaman->konflik_jadwal)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 font-medium">
                            ⚠ Terdapat konflik jadwal peminjaman. Resolusi diurutkan otomatis via Priority Scheduling.
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="button" onclick="document.getElementById('modal-komparasi-admin').classList.remove('hidden')"
                                class="w-full py-2.5 px-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-md shadow-blue-500/20 active:scale-[0.99]">
                            <span>⚡</span>
                            <span>Bandingkan: Pakai Algoritma vs Tidak</span>
                        </button>
                        <a href="{{ route('simulasi.algoritma') }}" class="block text-center text-[11px] font-bold text-blue-600 hover:underline mt-2">
                            Buka Lab Simulasi Lengkap →
                        </a>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-400 font-medium space-y-1">
                        <p>Dibuat pada: {{ $peminjaman->created_at->format('d/m/Y H:i') }}</p>
                        @if($peminjaman->disetujui_at)
                            <p>Disetujui pada: {{ $peminjaman->disetujui_at->format('d/m/Y H:i') }}{{ $peminjaman->disetujuiOleh ? ' oleh ' . $peminjaman->disetujuiOleh->name : '' }}</p>
                        @elseif($peminjaman->diverifikasi_at)
                            <p>Diverifikasi pada: {{ $peminjaman->diverifikasi_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Peminjam Detail --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Biodata Peminjam</h3>
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">NIK Pemohon</p>
                        <p class="font-mono font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->peminjam->nik ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">No. WhatsApp / HP</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->peminjam->no_hp ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Alamat Instansi / Rumah</p>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $peminjaman->peminjam->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Komparasi Algoritma Admin --}}
    <div id="modal-komparasi-admin" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-2xl shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between mb-4 border-b border-slate-100 pb-3">
                <div>
                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 rounded-lg mb-1">
                        Komparasi Keputusan Algoritma
                    </span>
                    <h3 class="font-extrabold text-slate-900 text-base">Perbandingan: Menggunakan Algoritma vs Tanpa Algoritma</h3>
                    <p class="text-xs text-slate-500 font-medium">Pengajuan: <strong>{{ $peminjaman->peminjam->name }}</strong> ({{ ucfirst($peminjaman->peminjam->jenis_peminjam ?? 'Umum') }}) · {{ $peminjaman->jumlah_pinjam ?? 1 }} Unit</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-komparasi-admin').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                    ✕
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                {{-- Tanpa Algoritma --}}
                <div class="bg-rose-50/50 border border-rose-200/80 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs font-bold">✕</span>
                        <h4 class="font-extrabold text-rose-900 text-sm">Tanpa Algoritma (FIFO Konvensional)</h4>
                    </div>
                    <ul class="text-xs text-rose-800/90 space-y-2 font-medium">
                        <li><strong>Metode:</strong> Diurutkan murni berdasarkan jam pengajuan ({{ $peminjaman->created_at->format('d M Y H:i') }} WIB).</li>
                        <li><strong>Logika:</strong> Tidak membedakan apakah pemohon adalah Dinas Pemda atau Umum. Siapa yang klik web duluan langsung memblokir tanggal.</li>
                        <li><strong>Kelemahan:</strong> Jika permohonan dinas pemerintah masuk belakangan setelah pemohon umum, agenda dinas penting langsung <strong>DITOLAK</strong>.</li>
                    </ul>
                </div>

                {{-- Dengan Algoritma --}}
                <div class="bg-blue-50/50 border border-blue-200/80 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold">⚡</span>
                        <h4 class="font-extrabold text-blue-950 text-sm">Dengan Priority Scheduling</h4>
                    </div>
                    <ul class="text-xs text-blue-900/90 space-y-2 font-medium">
                        <li><strong>Metode:</strong> Skor Bobot: Pemerintah (3) > Organisasi (2) > Umum (1).</li>
                        <li><strong>Status Pengajuan Ini:</strong> Skor <strong>{{ $peminjaman->priority_score }}/3</strong> ({{ \App\Services\PrioritySchedulingService::getLabelPrioritas($peminjaman->priority_score) }}).</li>
                        <li><strong>Keunggulan:</strong> Jika terjadi bentrok jadwal, permohonan dinas diprioritaskan, dan pemohon umum yang kalah prioritas dialihkan/dijadwalkan ulang secara adil.</li>
                    </ul>
                </div>
            </div>

            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl mb-4 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-medium">Ingin menguji skenario bentrok langsung di depan penguji?</span>
                <a href="{{ route('simulasi.algoritma') }}" class="font-bold text-blue-600 hover:underline flex items-center gap-1">
                    Buka Lab Simulasi Komparasi →
                </a>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('modal-komparasi-admin').classList.add('hidden')"
                        class="px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
