<x-app-layout>
    <x-slot name="title">Ajukan Pinjaman</x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                    ← Kembali
                </a>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Formulir Pengajuan Pinjaman</h2>
                <p class="text-xs font-medium text-slate-500">Lengkapi formulir di bawah ini untuk mengajukan peminjaman aset</p>
            </div>
        </div>


    @if($selectedAset)
        {{-- Selected Asset Card Banner --}}
        <div class="bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-transparent border border-blue-500/30 rounded-2xl p-4 mb-6 flex items-center gap-4 shadow-sm backdrop-blur-sm">
            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm flex-shrink-0 border border-blue-100">
                @if($selectedAset->foto)
                    <img src="{{ Storage::url($selectedAset->foto) }}" class="w-12 h-12 rounded-xl object-cover">
                @else
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                @endif
            </div>
            <div class="flex-1">
                <p class="font-extrabold text-slate-800 text-sm leading-tight">{{ $selectedAset->nama_aset }}</p>
                <p class="text-xs text-blue-600 font-semibold mt-0.5">{{ $selectedAset->kategori->nama ?? '-' }} · Kode: {{ $selectedAset->kode_aset }}</p>
            </div>
            <span class="text-xs bg-emerald-500/15 text-emerald-700 font-bold px-3 py-1.5 rounded-xl border border-emerald-500/30">
                Sisa Tersedia: {{ $selectedAset->stok_tersedia }} Unit ✓
            </span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <form method="POST" action="{{ route('peminjam.peminjaman.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="aset_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Aset Tersedia <span class="text-rose-500">*</span></label>
                        <select id="aset_id" name="aset_id" required
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('aset_id') border-rose-400 bg-rose-50/30 @enderror">
                            <option value="">-- Pilih Aset yang Ingin Dipinjam --</option>
                            @foreach($asets as $aset)
                                <option value="{{ $aset->id }}"
                                    data-stok="{{ $aset->stok_tersedia }}"
                                    {{ old('aset_id', $selectedAset?->id) == $aset->id ? 'selected' : '' }}>
                                    {{ $aset->nama_aset }} — Tersedia: {{ $aset->stok_tersedia }} unit ({{ $aset->kategori->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('aset_id') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Box Jumlah Barang yang Dipinjam --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="jumlah_pinjam" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Jumlah Barang yang Dipinjam <span class="text-rose-500">*</span>
                            </label>
                            <span id="badge-stok-info" class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-200/60">
                                Sisa Stok: {{ $selectedAset ? $selectedAset->stok_tersedia : '-' }} Unit
                            </span>
                        </div>
                        <div class="relative">
                            <input id="jumlah_pinjam" name="jumlah_pinjam" type="number" min="1"
                                   max="{{ $selectedAset ? $selectedAset->stok_tersedia : 999 }}"
                                   value="{{ old('jumlah_pinjam', 1) }}" required
                                   class="w-full px-4 py-3 pr-20 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('jumlah_pinjam') border-rose-400 bg-rose-50/30 @enderror"
                                   placeholder="Contoh: 5">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-xs font-bold text-slate-400">
                                Unit / Buah
                            </div>
                        </div>
                        <p id="helper-stok-text" class="text-[11px] text-slate-500 font-medium mt-1">
                            Masukkan jumlah unit fisik yang ingin Anda pinjam untuk kegiatan ini.
                        </p>
                        @error('jumlah_pinjam') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_pinjam" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Mulai Pinjam <span class="text-rose-500">*</span></label>
                            <input id="tanggal_pinjam" name="tanggal_pinjam" type="date"
                                   value="{{ old('tanggal_pinjam') }}" min="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('tanggal_pinjam') border-rose-400 bg-rose-50/30 @enderror">
                            @error('tanggal_pinjam') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tanggal_kembali" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Selesai Pinjam <span class="text-rose-500">*</span></label>
                            <input id="tanggal_kembali" name="tanggal_kembali" type="date"
                                   value="{{ old('tanggal_kembali') }}" required
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('tanggal_kembali') border-rose-400 bg-rose-50/30 @enderror">
                            @error('tanggal_kembali') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="keperluan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Keperluan & Alasan Peminjaman <span class="text-rose-500">*</span></label>
                        <textarea id="keperluan" name="keperluan" rows="4" required minlength="20"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white @error('keperluan') border-rose-400 bg-rose-50/30 @enderror"
                                  placeholder="Jelaskan secara rinci kegiatan dan tujuan penggunaan aset (min. 20 karakter)...">{{ old('keperluan') }}</textarea>
                        @error('keperluan') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dokumen_pendukung" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Dokumen Pendukung / Surat Permohonan (Opsional)</label>
                        <input id="dokumen_pendukung" name="dokumen_pendukung" type="file" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                        <p class="text-[11px] text-slate-400 font-medium mt-1">Format didukung: PDF, JPG, PNG · Ukuran maksimal: 5 MB</p>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                        Kirim Pengajuan Pinjaman →
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Status Prioritas Anda</h3>
                @php $ps = Auth::user()->priority_score; @endphp
                <div class="text-center py-4 bg-slate-50/70 rounded-xl border border-slate-100">
                    <p class="text-4xl font-black text-blue-600 tracking-tight">{{ $ps }}</p>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Skor Priority Scheduling</p>
                    <p class="text-xs font-bold mt-2 {{ $ps==3?'text-amber-600':($ps==2?'text-blue-600':'text-slate-600') }}">
                        {{ $ps==3?'⭐ Prioritas Tinggi (Pemerintah)':($ps==2?'💎 Prioritas Sedang (Organisasi)':'👤 Prioritas Normal (Umum)') }}
                    </p>
                </div>
                <p class="text-[11px] font-medium text-slate-400 text-center mt-3 leading-relaxed">
                    Algoritma Priority Scheduling akan memprioritaskan skor lebih tinggi jika terjadi konflik jadwal peminjaman aset.
                </p>

                {{-- Button Modal Komparasi Algoritma --}}
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modal-komparasi-algoritma').classList.remove('hidden')"
                            class="w-full py-2.5 px-3 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border border-blue-200 text-blue-700 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                        <span>⚡</span>
                        <span>Bandingkan: Pakai Algoritma vs Tidak</span>
                    </button>
                </div>
            </div>

            <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-5">
                <p class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-3">Alur Verifikasi System</p>
                <div class="space-y-2.5 text-xs font-medium text-blue-800">
                    <div class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">1</span> Pengajuan dikirim ke pengelola</div>
                    <div class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">2</span> Evaluasi kondisi aset (Decision Tree)</div>
                    <div class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">3</span> Pengelola Aset memverifikasi &amp; memberikan persetujuan</div>
                    <div class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">4</span> Surat pernyataan + QR barcode terbit</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Komparasi Algoritma --}}
    <div id="modal-komparasi-algoritma" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-2xl shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between mb-4 border-b border-slate-100 pb-3">
                <div>
                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800 rounded-lg mb-1">
                        Komparasi Algoritma Cerdas
                    </span>
                    <h3 class="font-extrabold text-slate-900 text-base">Perbedaan: Dengan Algoritma vs Tanpa Algoritma</h3>
                    <p class="text-xs text-slate-500 font-medium">Memahami bagaimana sistem menyelesaikan bentrok jadwal peminjaman aset</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-komparasi-algoritma').classList.add('hidden')"
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
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Prinsip:</strong> Siapa cepat dia dapat (First-Come First-Served murni).</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Kelemahan:</strong> Jika Masyarakat Umum daftar jam 08:00 dan Dinas Pemda mendaftar jam 08:15 untuk acara resmi mendesak, dinas resmi langsung <strong>DITOLAK</strong> karena kalah cepat klik web.</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Dampak:</strong> Fasilitas negara tidak dapat dimanfaatkan secara optimal untuk kepentingan publik yang lebih luas.</span>
                        </li>
                    </ul>
                </div>

                {{-- Dengan Algoritma --}}
                <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">✓</span>
                        <h4 class="font-extrabold text-emerald-900 text-sm">Dengan Priority Scheduling</h4>
                    </div>
                    <ul class="text-xs text-emerald-800/90 space-y-2 font-medium">
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Prinsip:</strong> Pembobotan skor instansi: Pemerintah (3) > Organisasi (2) > Umum (1).</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Keunggulan:</strong> Jika terjadi bentrok jadwal, sistem secara objektif mendahulukan permohonan dengan skor prioritas tertinggi.</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="font-bold">•</span>
                            <span><strong>Keadilan:</strong> Aturan FCFS tetap berlaku adil jika kedua pemohon memiliki skor prioritas yang sama.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 mb-4 text-xs font-medium text-slate-600">
                <p class="font-bold text-slate-800 mb-1">💡 Kasus Nyata di Sistem:</p>
                <p>Status akun Anda saat ini terdaftar sebagai <strong>{{ Auth::user()->jenis_peminjam ?? 'Umum' }}</strong> dengan skor <strong>{{ Auth::user()->priority_score }}/3</strong>. Jika aset yang Anda pilih tidak memiliki konflik dengan peminjam lain di tanggal yang sama, pengajuan Anda akan langsung diproses.</p>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('modal-komparasi-algoritma').classList.add('hidden')"
                        class="px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                    Tutup Penjelasan
                </button>
            </div>
        </div>
    </div>

    {{-- Script Dynamic Stock & Validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAset = document.getElementById('aset_id');
            const inputJumlah = document.getElementById('jumlah_pinjam');
            const badgeStok = document.getElementById('badge-stok-info');
            const helperText = document.getElementById('helper-stok-text');

            function syncStok() {
                const selectedOpt = selectAset.options[selectAset.selectedIndex];
                if (selectedOpt && selectedOpt.dataset.stok !== undefined && selectedOpt.value !== '') {
                    const stokTersedia = parseInt(selectedOpt.dataset.stok, 10);
                    inputJumlah.max = stokTersedia;
                    badgeStok.textContent = `Sisa Stok: ${stokTersedia} Unit`;
                    helperText.textContent = `Maksimal dapat meminjam ${stokTersedia} unit sesuai ketersediaan fisik saat ini.`;

                    if (parseInt(inputJumlah.value, 10) > stokTersedia) {
                        inputJumlah.value = stokTersedia > 0 ? stokTersedia : 1;
                    }
                } else {
                    badgeStok.textContent = 'Sisa Stok: -';
                    helperText.textContent = 'Pilih aset terlebih dahulu untuk melihat kuota unit yang tersedia.';
                }
            }

            selectAset.addEventListener('change', syncStok);
            // Run on initial load
            syncStok();
        });
    </script>
</x-app-layout>


