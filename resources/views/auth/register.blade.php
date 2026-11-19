<x-guest-layout>
    <x-slot name="title">Daftar Akun</x-slot>
    <x-slot name="cardWidth">max-w-xl</x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Akun Peminjam</h2>
        <p class="text-slate-500 text-xs font-medium mt-1">Lengkapi data diri Anda untuk mengajukan peminjaman aset publik Dispora</p>
    </div>


    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="space-y-4">
            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('name') border-rose-400 bg-rose-50/30 @enderror"
                       placeholder="Nama lengkap sesuai KTP">
                @error('name') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Active <span class="text-rose-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('email') border-rose-400 bg-rose-50/30 @enderror"
                       placeholder="nama@domain.com">
                @error('email') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIK KTP (16 Digit) <span class="text-rose-500">*</span></label>
                    <input id="nik" name="nik" type="text" value="{{ old('nik') }}" required
                           maxlength="16" minlength="16" inputmode="numeric" pattern="[0-9]{16}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('nik') border-rose-400 bg-rose-50/30 @enderror"
                           placeholder="16 digit angka NIK">
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Wajib tepat 16 digit angka</p>
                    @error('nik') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('no_hp') border-rose-400 bg-rose-50/30 @enderror"
                           placeholder="08xxxxxxxxxx">
                    @error('no_hp') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Jenis Peminjam --}}
            <div>
                <label for="jenis_peminjam" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Instansi / Peminjam <span class="text-rose-500">*</span></label>
                <select id="jenis_peminjam" name="jenis_peminjam" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('jenis_peminjam') border-rose-400 bg-rose-50/30 @enderror">
                    <option value="">-- Pilih Jenis Instansi --</option>
                    <option value="pemerintah" {{ old('jenis_peminjam') === 'pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                    <option value="organisasi" {{ old('jenis_peminjam') === 'organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="umum" {{ old('jenis_peminjam') === 'umum' ? 'selected' : '' }}>Umum / Masyarakat</option>
                </select>
                @error('jenis_peminjam') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Domisili <span class="text-rose-500">*</span></label>
                <textarea id="alamat" name="alamat" rows="2" required
                          class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition resize-none bg-slate-50/50 focus:bg-white @error('alamat') border-rose-400 bg-rose-50/30 @enderror"
                          placeholder="Alamat lengkap kota Palembang">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Foto KTP --}}
            <div>
                <label for="foto_ktp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Foto KTP <span class="text-rose-500">*</span></label>
                <input id="foto_ktp" name="foto_ktp" type="file" accept="image/*" required
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50/50">
                @error('foto_ktp') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('password') border-rose-400 bg-rose-50/30 @enderror"
                           placeholder="Min. 8 karakter">
                    @error('password') <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white"
                           placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <button type="submit"
                class="w-full mt-4 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
            Daftar Akun Sekarang
        </button>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-100 text-center">
        <p class="text-xs font-medium text-slate-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>

