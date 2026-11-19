<x-app-layout>
    <x-slot name="title">Edit Aset</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.aset.show', $aset) }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Detail Aset
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Data Aset & Inventaris</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Perbarui spesifikasi, status master, atau lokasi {{ $aset->nama_aset }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.aset.update', $aset) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Aset / Peralatan <span class="text-rose-500">*</span></label>
                        <input name="nama_aset" type="text" value="{{ old('nama_aset', $aset->nama_aset) }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('nama_aset') border-rose-400 bg-rose-50/30 @enderror">
                        @error('nama_aset') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Aset <span class="text-rose-500">*</span></label>
                        <select name="kategori_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $aset->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Master Aset <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            @foreach(['tersedia'=>'Tersedia','dipinjam'=>'Dipinjam','maintenance'=>'Maintenance','tidak_aktif'=>'Tidak Aktif'] as $s => $label)
                                <option value="{{ $s }}" {{ old('status', $aset->status) === $s ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Merk / Tipe / Spesifikasi</label>
                        <input name="merk" type="text" value="{{ old('merk', $aset->merk) }}"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tahun Pengadaan</label>
                        <input name="tahun_pengadaan" type="number" value="{{ old('tahun_pengadaan', $aset->tahun_pengadaan) }}"
                               min="2000" max="{{ date('Y') }}"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Unit <span class="text-rose-500">*</span></label>
                        <input name="jumlah" type="number" value="{{ old('jumlah', $aset->jumlah ?? 1) }}" min="1" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hak Akses Peminjaman <span class="text-rose-500">*</span></label>
                        <select name="bisa_dipinjam" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            <option value="1" {{ old('bisa_dipinjam', $aset->bisa_dipinjam ? '1' : '0') == '1' ? 'selected' : '' }}>✓ Dapat Dipinjam Publik (Tampil di Web Peminjam)</option>
                            <option value="0" {{ old('bisa_dipinjam', $aset->bisa_dipinjam ? '1' : '0') == '0' ? 'selected' : '' }}>🔒 Khusus Internal Dispora (Tidak Bisa Dipinjam)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi Ruang / Gedung</label>
                        <input name="lokasi" type="text" value="{{ old('lokasi', $aset->lokasi) }}"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap Aset</label>
                        <textarea name="deskripsi" rows="3"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white">{{ old('deskripsi', $aset->deskripsi) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ganti Foto Aset</label>
                        @if($aset->foto)
                            <div class="mb-3">
                                <img src="{{ Storage::url($aset->foto) }}" class="w-28 h-28 object-cover rounded-2xl border border-slate-200 shadow-sm">
                            </div>
                        @endif
                        <input name="foto" type="file" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                    </div>
                </div>

                <div class="flex gap-3 pt-3">
                    <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                        Simpan Perubahan →
                    </button>
                    <a href="{{ route('admin.aset.show', $aset) }}"
                       class="flex-1 py-3.5 border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-50 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

