<x-app-layout>
    <x-slot name="title">Detail User</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Manajemen User
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h2>
            <p class="text-xs font-medium text-slate-400 mt-0.5">{{ $user->email }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="px-4 py-2.5 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">Edit Profile</a>
            <form method="POST" action="{{ route('admin.users.toggle-aktif', $user) }}">
                @csrf
                <button type="submit"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl transition-all {{ $user->is_active ? 'border border-rose-200 text-rose-600 hover:bg-rose-50' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-md shadow-emerald-500/20' }}">
                    {{ $user->is_active ? 'Nonaktifkan Akun' : '✓ Verifikasi & Aktifkan Akun' }}
                </button>
            </form>
        </div>
    </div>

    @if(!$user->is_active)
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⏳</span>
                <div>
                    <h4 class="text-xs font-black text-amber-900 uppercase">Akun Menunggu Validasi & Verifikasi</h4>
                    <p class="text-xs text-amber-700 mt-0.5">Periksa kesesuaian NIK (16 digit) dan berkas KTP peminjam sebelum mengaktifkan akun.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.users.toggle-aktif', $user) }}" class="flex flex-col items-stretch sm:items-end gap-2">
                @csrf
                @if($user->role === 'peminjam')
                    <input type="hidden" name="dikecualikan_rekap" value="0">
                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-amber-900 cursor-pointer">
                        <input type="checkbox" name="dikecualikan_rekap" value="1" {{ $user->dikecualikan_rekap ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-amber-300 text-orange-600 focus:ring-orange-500">
                        Tandai: kecualikan dari rekap bulanan
                    </label>
                @endif
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-md shadow-emerald-500/20 whitespace-nowrap">
                    ✓ Verifikasi & Aktifkan Akun
                </button>
            </form>
        </div>
    @endif

    @if($user->role === 'peminjam' && $user->dikecualikan_rekap)
        <div class="mb-6 p-4 rounded-2xl bg-orange-50 border border-orange-200 flex items-center gap-3">
            <span class="text-2xl">🚫</span>
            <div>
                <h4 class="text-xs font-black text-orange-900 uppercase">Akun Dikecualikan dari Rekap Bulanan</h4>
                <p class="text-xs text-orange-800 mt-0.5">
                    Aktivitas peminjaman & pengembalian akun ini tidak dihitung dalam rekap bulanan.
                    @if($user->catatan_pengecualian) <br><strong>Catatan:</strong> {{ $user->catatan_pengecualian }} @endif
                </p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Foto KTP --}}
            @if($user->foto_ktp)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Berkas Identitas (KTP)</h3>
                    <img src="{{ Storage::url($user->foto_ktp) }}" alt="KTP {{ $user->name }}"
                         class="max-w-xs rounded-xl border border-slate-200 shadow-sm">
                </div>
            @endif

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Riwayat Peminjaman Aset User</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                                <th class="px-5 py-3">No. Pengajuan</th>
                                <th class="px-5 py-3">Aset</th>
                                <th class="px-5 py-3">Tanggal Pinjam</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($riwayatPeminjaman as $rp)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-3 font-mono font-bold text-slate-500">{{ $rp->nomor_pengajuan }}</td>
                                    <td class="px-5 py-3 font-bold text-slate-800">{{ $rp->aset->nama_aset ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-400">{{ $rp->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3">
                                        @php
                                            $statusBadge = match($rp->status) {
                                                'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                                'ditolak'      => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default        => 'bg-slate-50 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full border {{ $statusBadge }} capitalize">
                                            {{ ucfirst($rp->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-8 text-center text-xs font-medium text-slate-400">Belum ada riwayat peminjaman aset</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($riwayatPeminjaman->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100">{{ $riwayatPeminjaman->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <div class="text-center pb-4 border-b border-slate-100">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-2xl font-black text-white mx-auto mb-3 shadow-md shadow-blue-500/20">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @php
                        $roleBadge = match($user->role) {
                            'admin'     => 'bg-rose-50 text-rose-700 border-rose-200',
                            'pengelola' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'peminjam'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default     => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="px-3 py-1 text-[11px] font-bold rounded-full border {{ $roleBadge }} capitalize">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIK</p>
                        <p class="font-mono font-bold text-slate-800 text-sm mt-0.5">{{ $user->nik ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Telepon / HP</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $user->no_hp ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis Instansi</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5 capitalize">{{ $user->jenis_peminjam ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            @if($user->role === 'peminjam')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-1">Status Rekap Bulanan</h3>
                    <p class="text-xs font-medium mb-3 {{ $user->dikecualikan_rekap ? 'text-orange-700' : 'text-emerald-700' }}">
                        {{ $user->dikecualikan_rekap ? '🚫 Tidak dihitung dalam rekap' : '✓ Dihitung dalam rekap' }}
                    </p>
                    <form method="POST" action="{{ route('admin.users.toggle-rekap', $user) }}" class="space-y-2">
                        @csrf
                        @unless($user->dikecualikan_rekap)
                            <input name="catatan_pengecualian" type="text" maxlength="500" placeholder="Alasan pengecualian (opsional)"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none bg-slate-50/50">
                        @endunless
                        <button type="submit"
                                class="w-full py-2.5 text-xs font-bold rounded-xl transition-all {{ $user->dikecualikan_rekap ? 'border border-emerald-200 text-emerald-700 hover:bg-emerald-50' : 'bg-orange-600 hover:bg-orange-700 text-white shadow-md shadow-orange-500/20' }}">
                            {{ $user->dikecualikan_rekap ? '↺ Masukkan Kembali ke Rekap' : '🚫 Kecualikan dari Rekap' }}
                        </button>
                    </form>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Priority Scheduling Score</h3>
                <div class="text-center py-3 bg-slate-50/70 rounded-xl border border-slate-100">
                    <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $user->priority_score }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Skor Tingkat Prioritas</p>
                    <span class="mt-3 inline-block px-3 py-1 text-[10px] font-extrabold rounded-full border
                        {{ $user->priority_score==3?'bg-amber-50 text-amber-700 border-amber-200':'' }}
                        {{ $user->priority_score==2?'bg-blue-50 text-blue-700 border-blue-200':'' }}
                        {{ $user->priority_score==1?'bg-slate-100 text-slate-600 border-slate-200':'' }}">
                        {{ $user->priority_score==3?'★ Priority 3 (Tinggi)':($user->priority_score==2?'◆ Priority 2 (Sedang)':'● Priority 1 (Normal)') }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 text-xs text-slate-500 font-medium space-y-2">
                <div class="flex items-center justify-between">
                    <span>Status Akun:</span>
                    <span class="font-bold {{ $user->is_active ? 'text-emerald-600' : 'text-rose-600' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Terdaftar Pada:</span>
                    <span class="font-bold text-slate-700">{{ $user->created_at->format('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

