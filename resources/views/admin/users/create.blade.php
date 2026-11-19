<x-app-layout>
    <x-slot name="title">Tambah User</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Manajemen User
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Buat Akun User Baru</h2>
            <p class="text-xs font-medium text-slate-500 mt-0.5">Daftarkan akun pengguna baru untuk Admin, Pengelola, atau Peminjam</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                        <input name="name" type="text" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                               placeholder="Nama lengkap beserta gelar jika ada">
                        @error('name') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                        <input name="email" type="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                               placeholder="email@domain.com">
                        @error('email') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Role Pengguna <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            <option value="peminjam" {{ old('role')==='peminjam'?'selected':'' }}>Peminjam (Masyarakat / Instansi)</option>
                            <option value="pengelola" {{ old('role')==='pengelola'?'selected':'' }}>Pengelola (Tim Aset)</option>
                            <option value="admin" {{ old('role')==='admin'?'selected':'' }}>Administrator</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIK (Nomor Induk Kependudukan)</label>
                        <input name="nik" type="text" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit NIK"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input name="no_hp" type="text" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Klasifikasi Instansi / Peminjam</label>
                        <select name="jenis_peminjam" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white">
                            <option value="umum">Umum (Masyarakat Individu) — Priority 1</option>
                            <option value="organisasi">Organisasi / Komunitas — Priority 2</option>
                            <option value="pemerintah">Pemerintah / Instansi Dinas — Priority 3</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 p-4 bg-orange-50/60 border border-orange-200/80 rounded-xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="hidden" name="dikecualikan_rekap" value="0">
                            <input type="checkbox" name="dikecualikan_rekap" value="1" {{ old('dikecualikan_rekap') ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 rounded border-orange-300 text-orange-600 focus:ring-orange-500">
                            <span>
                                <span class="block text-xs font-bold text-orange-900 uppercase tracking-wider">Kecualikan dari Rekap Bulanan</span>
                                <span class="block text-xs text-orange-800/80 font-medium mt-0.5">Khusus role Peminjam. Peminjaman & pengembalian akun ini tetap berjalan normal (disetujui/ditolak Pengelola), tetapi tidak dihitung dalam rekap bulanan.</span>
                            </span>
                        </label>
                        <input name="catatan_pengecualian" type="text" value="{{ old('catatan_pengecualian') }}" maxlength="500"
                               placeholder="Alasan / catatan pengecualian (opsional)"
                               class="mt-3 w-full px-4 py-2.5 border border-orange-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                        <input name="password" type="password" required minlength="8"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input name="password_confirmation" type="password" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                               placeholder="Ulangi password">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white"
                                  placeholder="Alamat domisili atau instansi peminjam">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
                    Simpan User Baru →
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

