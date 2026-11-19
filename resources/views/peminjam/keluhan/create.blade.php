<x-app-layout>
    <x-slot name="title">Laporkan Keluhan</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('peminjam.keluhan.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Form Laporan Keluhan</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Sampaikan keluhan atau masalah terkait peminjaman aset</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <form method="POST" action="{{ route('peminjam.keluhan.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="peminjaman_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peminjaman Terkait (Opsional)</label>
                    <select id="peminjaman_id" name="peminjaman_id"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 outline-none transition bg-slate-50/50 focus:bg-white">
                        <option value="">-- Tidak Terkait Peminjaman Spesifik --</option>
                        @foreach($peminjamanList as $p)
                            <option value="{{ $p->id }}" {{ old('peminjaman_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nomor_pengajuan }} - {{ $p->aset->nama_aset ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Ringkas Keluhan <span class="text-rose-500">*</span></label>
                    <input id="judul" name="judul" type="text" value="{{ old('judul') }}" required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 outline-none transition bg-slate-50/50 focus:bg-white @error('judul') border-rose-400 bg-rose-50/30 @enderror"
                           placeholder="Contoh: Proyektor tidak mau menyala saat digunakan">
                    @error('judul') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="isi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Penjelasan Keluhan <span class="text-rose-500">*</span></label>
                    <textarea id="isi" name="isi" rows="5" required minlength="20"
                              class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white @error('isi') border-rose-400 bg-rose-50/30 @enderror"
                              placeholder="Jelaskan kronologi atau permasalahan aset secara lengkap (min. 20 karakter)...">{{ old('isi') }}</textarea>
                    @error('isi') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lampiran" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Bukti Foto/Dokumen (Opsional)</label>
                    <input id="lampiran" name="lampiran" type="file" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                </div>

                <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-amber-500/25 active:scale-[0.99]">
                    Kirim Laporan Keluhan →
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

