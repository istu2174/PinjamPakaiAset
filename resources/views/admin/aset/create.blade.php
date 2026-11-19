<x-app-layout>
    <x-slot name="title">Tambah Aset</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.aset.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Manajemen Aset
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Registrasi Aset Baru</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Tambahkan data aset publik ke dalam sistem inventaris Dispora</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">

            <form method="POST" action="{{ route('admin.aset.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Aset / Peralatan <span class="text-rose-500">*</span></label>
                        <input name="nama_aset" type="text" value="{{ old('nama_aset') }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('nama_aset') border-rose-400 bg-rose-50/30 @enderror"
                               placeholder="Contoh: Proyektor Epson EB-X500">
                        @error('nama_aset') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Aset <span class="text-rose-500">*</span></label>
                        <select name="kategori_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Aset Unik <span class="text-rose-500">*</span></label>
                        <input name="kode_aset" type="text" value="{{ old('kode_aset') }}" required placeholder="AST-001"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Merk / Tipe / Spesifikasi</label>
                        <input name="merk" type="text" value="{{ old('merk') }}" placeholder="Contoh: Sony / Honda / Asus"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tahun Pengadaan</label>
                        <input name="tahun_pengadaan" type="number" value="{{ old('tahun_pengadaan') }}" min="2000" max="{{ date('Y') }}" placeholder="2023"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Unit <span class="text-rose-500">*</span></label>
                        <input name="jumlah" type="number" value="{{ old('jumlah', 1) }}" min="1" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hak Akses Peminjaman <span class="text-rose-500">*</span></label>
                        <select name="bisa_dipinjam" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            <option value="1" {{ old('bisa_dipinjam', '1') == '1' ? 'selected' : '' }}>✓ Dapat Dipinjam Publik (Tampil di Web Peminjam)</option>
                            <option value="0" {{ old('bisa_dipinjam') === '0' ? 'selected' : '' }}>🔒 Khusus Internal Dispora (Tidak Bisa Dipinjam)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi Penyimpanan Aset</label>
                        <input name="lokasi" type="text" value="{{ old('lokasi') }}" placeholder="Contoh: Gudang B / Ruang Rapat Lt. 2"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap Aset</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"
                                  placeholder="Penjelasan detail fasilitas, kelengkapan, atau syarat khusus peminjaman...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Foto Aset (Opsional)</label>
                        <input name="foto" type="file" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                    Simpan Aset Baru →
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

