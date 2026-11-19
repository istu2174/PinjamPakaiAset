<x-app-layout>
    <x-slot name="title">Detail Peminjaman</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline mb-1">
                ← Kembali ke Riwayat
            </a>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan Pinjaman</h2>
            <p class="text-xs font-mono font-bold text-slate-400 mt-0.5">{{ $peminjaman->nomor_pengajuan }}</p>
        </div>

        @if($peminjaman->surat)
            <a href="{{ route('peminjam.peminjaman.surat.download', $peminjaman) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/20 active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Surat PDF
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-3 border-b border-slate-100">Informasi Peminjaman Aset</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Aset</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->aset->nama_aset ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori Aset</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->aset->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Mulai Pinjam</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Selesai Pinjam</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-blue-50/60 rounded-xl border border-blue-100/80 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-blue-900 uppercase tracking-wider">Jumlah Barang Dipinjam</p>
                            <p class="text-xs text-blue-700 font-medium mt-0.5">Kuantitas fisik aset yang diajukan</p>
                        </div>
                        <span class="text-sm font-black text-blue-700 bg-white px-3 py-1 rounded-lg border border-blue-200 shadow-sm">
                            {{ $peminjaman->jumlah_pinjam ?? 1 }} Unit
                        </span>
                    </div>
                    <div class="sm:col-span-2 p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keperluan & Alasan</p>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $peminjaman->keperluan }}</p>
                    </div>
                </div>
            </div>

            @if($peminjaman->catatan_pengelola)
                <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-5">
                    <p class="font-bold text-blue-900 text-xs uppercase tracking-wider mb-1">Catatan dari Pengelola</p>
                    <p class="text-xs text-blue-800 font-medium leading-relaxed">{{ $peminjaman->catatan_pengelola }}</p>
                </div>
            @endif

            @if($peminjaman->alasan_penolakan)
                <div class="bg-rose-50/70 border border-rose-200/80 rounded-2xl p-5">
                    <p class="font-bold text-rose-900 text-xs uppercase tracking-wider mb-1">Alasan Penolakan</p>
                    <p class="text-xs text-rose-800 font-medium leading-relaxed">{{ $peminjaman->alasan_penolakan }}</p>
                </div>
            @endif

            {{-- Batal Form --}}
            @if($peminjaman->status === 'pending')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Batalkan Pengajuan</h3>
                    <p class="text-xs text-slate-500 font-medium mb-4">Anda dapat membatalkan pengajuan ini selama masih berstatus Menunggu Verifikasi.</p>
                    <form method="POST" action="{{ route('peminjam.peminjaman.batalkan', $peminjaman) }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                        @csrf
                        <input type="hidden" name="alasan_pembatalan" value="Dibatalkan oleh peminjam">
                        <button type="submit"
                                class="px-4 py-2.5 border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold rounded-xl transition-all">
                            Batalkan Pengajuan Ini
                        </button>
                    </form>
                </div>
            @endif

            {{-- Keluhan Form Button --}}
            @if(in_array($peminjaman->status, ['dipinjam','selesai','disetujui']))
                <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-amber-900 text-xs uppercase tracking-wider">Kendala atau Kerusakan Aset?</h3>
                        <p class="text-xs text-amber-800 font-medium mt-0.5">Sampaikan keluhan atau masukan jika terjadi masalah saat peminjaman.</p>
                    </div>
                    <a href="{{ route('peminjam.keluhan.create') }}"
                       class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex-shrink-0">
                        + Laporkan Keluhan
                    </a>
                </div>
            @endif
        </div>

        {{-- Sidebar Panel --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Pengajuan</p>
                    @php
                        $statusBadge = match($peminjaman->status) {
                            'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                            'diverifikasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'dipinjam'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'selesai'      => 'bg-slate-100 text-slate-600 border-slate-200',
                            'ditolak', 'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default        => 'bg-slate-50 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <span class="inline-block px-3 py-1.5 text-xs font-extrabold rounded-full border {{ $statusBadge }} capitalize">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Skor Prioritas</p>
                    <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $peminjaman->priority_score }}<span class="text-sm font-normal text-slate-400">/3</span></p>
                </div>

                <div class="text-[11px] font-medium text-slate-400 space-y-1.5 pt-3 border-t border-slate-100">
                    <p>Dibuat: <strong class="text-slate-600">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</strong></p>
                    @if($peminjaman->diverifikasi_at)
                        <p>Diverifikasi: <strong class="text-slate-600">{{ $peminjaman->diverifikasi_at->format('d/m/Y H:i') }}</strong></p>
                    @endif
                </div>
            </div>

            {{-- Timeline Stepper --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">Tahapan Alur Verifikasi</h3>
                @php
                    $steps = ['pending'=>'Pengajuan','disetujui'=>'Disetujui Pengelola','dipinjam'=>'Dipinjam','selesai'=>'Selesai'];
                    $statusStep = $peminjaman->status === 'diverifikasi' ? 'pending' : $peminjaman->status;
                    $currIdx = array_search($statusStep, array_keys($steps));
                @endphp
                <div class="space-y-4 relative">
                    @foreach($steps as $key => $label)
                        @php $idx = array_search($key, array_keys($steps)); @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-extrabold flex-shrink-0 transition-all
                                {{ $currIdx !== false && $idx <= $currIdx ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 text-slate-400' }}">
                                {{ $idx + 1 }}
                            </div>
                            <span class="text-xs {{ $currIdx !== false && $idx <= $currIdx ? 'font-bold text-slate-800' : 'font-medium text-slate-400' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

