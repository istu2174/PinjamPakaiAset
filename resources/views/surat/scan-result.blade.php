<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat Barcode — SIPANDA Dispora Palembang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden selection:bg-blue-500 selection:text-white">
    <!-- Ambient Background Blur Effects -->
    <div class="fixed top-1/4 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-1/4 -right-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 max-w-lg w-full shadow-2xl border border-slate-100 relative z-10">
        {{-- Header Branding --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-lg shadow-blue-500/25">
                <span class="text-white font-extrabold text-xl tracking-wider">S</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">SIPANDA</h1>
            <p class="text-xs font-semibold text-slate-500 mt-1 leading-relaxed">
                Sistem Informasi Peminjaman Aset Publik<br>
                <span class="text-slate-400 font-medium">Dinas Kepemudaan dan Olahraga Kota Palembang</span>
            </p>
        </div>

        {{-- Status Badge --}}
        <div class="bg-emerald-50 border border-emerald-200/80 rounded-2xl p-4 flex items-center justify-center gap-3 mb-8 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-emerald-500/30">
                ✓
            </div>
            <div>
                <p class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider">Surat Pernyataan Sah & Terverifikasi</p>
                <p class="text-[11px] font-medium text-emerald-600">Dokumen terdaftar resmi pada database Dispora Kota Palembang</p>
            </div>
        </div>

        {{-- Info Details Grid --}}
        <div class="space-y-4 mb-8 text-xs">
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">No. Pengajuan</p>
                    <p class="font-mono font-bold text-slate-800 text-sm">{{ $peminjaman->nomor_pengajuan }}</p>
                </div>
                <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pinjaman</p>
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-extrabold rounded-full bg-emerald-100 text-emerald-700 capitalize">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </div>
            </div>

            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Peminjam / Pemohon</p>
                <p class="font-extrabold text-slate-800 text-sm">{{ $peminjaman->peminjam->name }}</p>
            </div>

            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Aset yang Dipinjam</p>
                <p class="font-extrabold text-slate-800 text-sm">{{ $peminjaman->aset->nama_aset }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mulai Pinjam</p>
                    <p class="font-extrabold text-slate-800">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
                </div>
                <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pengembalian</p>
                    <p class="font-extrabold text-slate-800">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
                </div>
            </div>

            <div class="p-3.5 bg-slate-50/80 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keperluan Peminjaman</p>
                <p class="font-medium text-slate-700 leading-relaxed">{{ $peminjaman->keperluan }}</p>
            </div>
        </div>

        <div class="p-4 bg-blue-50/80 border border-blue-200/80 rounded-2xl text-xs mb-8">
            <div class="flex items-center gap-2 font-bold text-blue-900 mb-1">
                <span>📷 Audit Barcode Verification</span>
            </div>
            <p class="text-blue-700 font-medium">Terakhir diakses: {{ $surat->barcode_diakses_at?->format('d M Y, H:i') ?? '-' }} WIB</p>
        </div>

        <div class="text-center pt-4 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
            SIPANDA &copy; {{ date('Y') }} — Dinas Kepemudaan dan Olahraga Kota Palembang
        </div>
    </div>
</body>
</html>

