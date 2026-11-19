<x-app-layout>
    <x-slot name="title">Kategori Aset</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Kategori Aset</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Pengelompokan jenis dan klasifikasi barang inventaris Dispora</p>
        </div>
        <a href="{{ route('admin.aset.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
            ← Kembali ke Daftar Aset
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Form Tambah Kategori --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Tambah Kategori Baru</h3>
            <form method="POST" action="{{ route('admin.aset.kategori.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input name="nama" type="text" value="{{ old('nama') }}" required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('nama') border-rose-400 bg-rose-50/30 @enderror"
                           placeholder="Contoh: Peralatan Olahraga / Sound System">
                    @error('nama') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Kategori (Opsional)</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"
                              placeholder="Deskripsi singkat mengenai barang dalam kategori ini...">{{ old('deskripsi') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                    + Simpan Kategori Baru
                </button>
            </form>
        </div>

        {{-- Daftar Kategori --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Daftar Kategori Terdaftar</h3>
                <span class="px-2.5 py-0.5 text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 rounded-full">
                    Total: {{ $kategoris->total() }}
                </span>
            </div>
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($kategoris as $k)
                    <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 transition group">
                        <div>
                            <p class="font-extrabold text-slate-800 text-sm group-hover:text-blue-600 transition">{{ $k->nama }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $k->asets_count ?? 0 }} Aset Terkait · {{ $k->deskripsi ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-2.5 font-bold">
                            <button onclick="openEditModal({{ $k->id }}, '{{ addslashes($k->nama) }}', '{{ addslashes($k->deskripsi) }}')"
                                    class="text-xs text-slate-600 hover:text-slate-900 hover:underline">Edit</button>
                            <form method="POST" action="{{ route('admin.aset.kategori.destroy', $k) }}"
                                  onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama) }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 hover:underline disabled:opacity-40 disabled:no-underline"
                                        {{ $k->asets_count ? 'disabled title=Ada aset yang menggunakan kategori ini' : '' }}>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-xs font-medium text-slate-400">Belum ada kategori terdaftar</div>
                @endforelse
            </div>
            @if($kategoris->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">{{ $kategoris->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Modal Edit Kategori --}}
    <div id="modal-edit-kategori" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl border border-slate-100">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Edit Kategori Aset</h3>
            <p class="text-xs font-medium text-slate-500 mb-4">Perbarui nama atau deskripsi kategori</p>
            <form id="form-edit-kategori" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                    <input id="edit-nama" name="nama" type="text" required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="3"
                              class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-blue-500/20">Simpan Perubahan</button>
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 py-3 border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, deskripsi) {
            document.getElementById('form-edit-kategori').action = '/admin/aset/kategori/' + id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-deskripsi').value = deskripsi || '';
            document.getElementById('modal-edit-kategori').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('modal-edit-kategori').classList.add('hidden');
        }
    </script>
</x-app-layout>

