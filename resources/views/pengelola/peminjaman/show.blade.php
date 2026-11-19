<x-app-layout>
    <x-slot name="title">Verifikasi Peminjaman</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('pengelola.peminjaman.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Daftar Peminjaman
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan Peminjaman</h2>
            <p class="text-xs font-mono font-bold text-slate-400 mt-0.5">{{ $peminjaman->nomor_pengajuan }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Info Pengajuan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-3 border-b border-slate-100">Informasi Permohonan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Peminjam</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->peminjam->name ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis Instansi / Priority</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5 capitalize">{{ $peminjaman->peminjam->jenis_peminjam ?? '-' }} — Skor {{ $peminjaman->priority_score }}/3</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aset Dipinjam</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->aset->nama_aset ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Aset Master</p>
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $peminjaman->aset->status === 'tersedia' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                            {{ ucfirst($peminjaman->aset->status ?? '-') }}
                        </span>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pinjam</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Kembali</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-blue-950 uppercase tracking-wider">Jumlah Unit yang Diajukan</p>
                            <p class="text-xs text-blue-700 font-medium mt-0.5">Sisa stok aset fisik saat ini: {{ $peminjaman->aset->stok_tersedia }} unit</p>
                        </div>
                        <span class="text-sm font-black text-blue-800 bg-white px-3.5 py-1.5 rounded-xl border border-blue-200 shadow-sm">
                            {{ $peminjaman->jumlah_pinjam ?? 1 }} Unit
                        </span>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keperluan & Alasan Peminjaman</p>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $peminjaman->keperluan }}</p>
                    </div>
                </div>
            </div>

            {{-- Kondisi Aset (Decision Tree) --}}
            @if($peminjaman->aset->kondisiTerbaru)
                @php $k = $peminjaman->aset->kondisiTerbaru; @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Hasil Klasifikasi Decision Tree</span>
                        <a href="{{ route('pengelola.aset.penilaian', $peminjaman->aset) }}" class="text-blue-600 hover:underline font-semibold capitalize text-xs">Penilaian Baru →</a>
                    </h3>
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $dtBadge = match($k->hasil_decision_tree) {
                                'layak_pakai'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'perlu_perawatan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'layak_lelang'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                default           => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="px-3.5 py-1.5 text-xs font-extrabold rounded-full border {{ $dtBadge }}">
                            ✔ Klasifikasi DT: {{ str_replace('_',' ', ucfirst($k->hasil_decision_tree)) }}
                        </span>
                        <span class="text-[11px] font-medium text-slate-400">Evaluasi {{ $k->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-xs text-center">
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100"><p class="text-[10px] font-bold text-slate-400 uppercase">Kondisi Fisik</p><p class="font-black text-slate-800 text-lg mt-0.5">{{ $k->kondisi_fisik }}/5</p></div>
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100"><p class="text-[10px] font-bold text-slate-400 uppercase">Usia Aset</p><p class="font-black text-slate-800 text-lg mt-0.5">{{ $k->usia_aset }} Thn</p></div>
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-100"><p class="text-[10px] font-bold text-slate-400 uppercase">Perawatan Rutin</p><p class="font-black text-slate-800 text-lg mt-0.5">{{ $k->riwayat_perawatan_rutin ? 'Ya' : 'Tidak' }}</p></div>
                    </div>
                </div>
            @else
                <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 text-xs text-amber-800 flex items-center justify-between">
                    <div>
                        <p class="font-bold">⚠ Aset Belum Memiliki Evaluasi Decision Tree</p>
                        <p class="font-medium mt-0.5">Lakukan penilaian kriteria fisik dan kondisi aset terlebih dahulu.</p>
                    </div>
                    <a href="{{ route('pengelola.aset.penilaian', $peminjaman->aset) }}" class="px-3.5 py-2 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition flex-shrink-0">
                        Evaluasi DT →
                    </a>
                </div>
            @endif

            {{-- Action Form --}}
            @if(in_array($peminjaman->status, ['pending', 'diverifikasi']))
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-1">Keputusan Pengelola Aset</h3>
                    <p class="text-xs text-slate-500 font-medium mb-4">Keputusan Anda bersifat final. Jika disetujui, surat pernyataan PDF akan dibuat otomatis untuk peminjam.</p>
                    <form method="POST" action="{{ route('pengelola.peminjaman.setujui', $peminjaman) }}" class="mb-4">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Pengelola (Opsional)</label>
                            <textarea name="catatan_pengelola" rows="2"
                                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white resize-none"
                                      placeholder="Catatan untuk peminjam..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-emerald-500/25 active:scale-[0.99]">
                            ✓ Setujui Peminjaman
                        </button>
                    </form>

                    <div class="border-t border-slate-100 pt-4">
                        <button onclick="document.getElementById('modal-tolak-p').classList.remove('hidden')"
                                class="w-full py-3 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold uppercase tracking-wider rounded-xl transition-all">
                            ✕ Tolak Permohonan Pinjaman
                        </button>
                    </div>
                </div>
            @endif

            {{-- Surat Pernyataan PDF --}}
            @if(in_array($peminjaman->status, ['disetujui', 'dipinjam', 'selesai']))
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-0.5">Surat Pernyataan Peminjaman</h3>
                        <p class="text-xs text-slate-400 font-medium">
                            {{ $peminjaman->surat ? 'Nomor: ' . $peminjaman->surat->nomor_surat : 'Surat belum dibuat' }} · dilengkapi QR Code verifikasi
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('surat.pdf', $peminjaman) }}" target="_blank"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                            Lihat PDF
                        </a>
                        <a href="{{ route('surat.pdf', [$peminjaman, 'download' => 1]) }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl border border-blue-200 transition-all">
                            Unduh
                        </a>
                        <form method="POST" action="{{ route('pengelola.peminjaman.surat.generate', $peminjaman) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl transition-all">
                                ↻ Buat Ulang PDF
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($peminjaman->status === 'disetujui')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Konfirmasi Serah Terima Barang</h3>
                    <p class="text-xs text-slate-500 font-medium mb-4">Klik tombol di bawah setelah barang fisik diserahkan kepada peminjam.</p>
                    <form method="POST" action="{{ route('pengelola.peminjaman.konfirmasi-ambil', $peminjaman) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-500/20">
                            ✓ Konfirmasi Aset Telah Diambil Peminjam
                        </button>
                    </form>
                </div>
            @endif

            @if($peminjaman->status === 'dipinjam')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Konfirmasi Pengembalian Aset</h3>
                    <form method="POST" action="{{ route('pengelola.peminjaman.konfirmasi-kembali', $peminjaman) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kondisi Fisik Saat Kembali <span class="text-rose-500">*</span></label>
                                <select name="kondisi_saat_kembali" required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition bg-slate-50/50">
                                    <option value="baik">Sangat Baik / Utuh</option>
                                    <option value="rusak_ringan">Rusak Ringan (Perlu Perawatan)</option>
                                    <option value="rusak_berat">Rusak Berat (Tidak Layak)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Pengembalian</label>
                                <textarea name="catatan" rows="2"
                                          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition resize-none bg-slate-50/50"
                                          placeholder="Keterangan kelengkapan saat pengembalian..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-500/20">
                                ✓ Proses Pengembalian Finished
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Peminjaman</p>
                    @php
                        $statusBadge = match($peminjaman->status) {
                            'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                            'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                            'ditolak'      => 'bg-rose-50 text-rose-700 border-rose-200',
                            default        => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="inline-block px-3 py-1.5 text-xs font-extrabold rounded-full border {{ $statusBadge }} capitalize">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Priority Scheduling Widget</h3>
                <div class="text-center py-3 bg-slate-50/70 rounded-xl border border-slate-100 mb-4">
                    <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $peminjaman->priority_score }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Skor Antrean</p>
                </div>
                <div class="space-y-2 text-xs font-medium">
                    @foreach([3=>'Pemerintah (Tinggi)',2=>'Organisasi (Sedang)',1=>'Umum (Normal)'] as $s=>$l)
                        <div class="flex items-center gap-2.5 p-2 rounded-lg {{ $peminjaman->priority_score == $s ? 'bg-blue-50/80 font-bold text-blue-900 border border-blue-200/50' : 'text-slate-400' }}">
                            <div class="w-2 h-2 rounded-full {{ $peminjaman->priority_score == $s ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                            <span>{{ $l }}</span>
                        </div>
                    @endforeach
                </div>
                @if($peminjaman->konflik_jadwal)
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-xs font-semibold text-amber-800 flex items-center gap-2">
                        <span>⚠</span> Konflik jadwal terdeteksi! Skor prioritas digunakan untuk memutuskan antrean.
                    </div>
                @endif

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modal-komparasi-pengelola').classList.remove('hidden')"
                            class="w-full py-2.5 px-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-md shadow-blue-500/20 active:scale-[0.99]">
                        <span>⚡</span>
                        <span>Bandingkan: Pakai Algoritma vs Tidak</span>
                    </button>
                    <a href="{{ route('simulasi.algoritma') }}" class="block text-center text-[11px] font-bold text-blue-600 hover:underline mt-2">
                        Buka Lab Simulasi Lengkap →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Komparasi Algoritma Pengelola --}}
    <div id="modal-komparasi-pengelola" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-2xl shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between mb-4 border-b border-slate-100 pb-3">
                <div>
                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 rounded-lg mb-1">
                        Komparasi Algoritma Antrean
                    </span>
                    <h3 class="font-extrabold text-slate-900 text-base">Evaluasi Pengajuan: Pakai Algoritma vs Tanpa Algoritma</h3>
                    <p class="text-xs text-slate-500 font-medium">Pengajuan: <strong>{{ $peminjaman->peminjam->name }}</strong> ({{ ucfirst($peminjaman->peminjam->jenis_peminjam ?? 'Umum') }}) · {{ $peminjaman->jumlah_pinjam ?? 1 }} Unit</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-komparasi-pengelola').classList.add('hidden')"
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
                <button type="button" onclick="document.getElementById('modal-komparasi-pengelola').classList.add('hidden')"
                        class="px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
    <div id="modal-tolak-p" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl border border-slate-100">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Tolak Pengajuan Peminjaman</h3>
            <p class="text-xs font-medium text-slate-500 mb-4">Berikan alasan penolakan yang akan dikirimkan kepada peminjam</p>
            <form method="POST" action="{{ route('pengelola.peminjaman.tolak', $peminjaman) }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="alasan_penolakan" rows="3" required
                              class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 outline-none transition bg-slate-50/50 focus:bg-white resize-none"
                              placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-rose-500/20">Tolak Permohonan</button>
                    <button type="button" onclick="document.getElementById('modal-tolak-p').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

