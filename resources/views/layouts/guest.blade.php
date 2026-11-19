<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIPANDA' }} - Sistem Informasi Peminjaman Aset Dinas</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.03);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-800 antialiased selection:bg-blue-500 selection:text-white relative overflow-x-hidden">

    <div class="w-full {{ $cardWidth ?? 'max-w-md' }} relative z-10 my-6 transition-all">

        {{-- Logo & Brand Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 shadow-lg shadow-blue-500/25 border border-white/20 mb-3 transform hover:scale-105 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 8v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">SIPANDA</h1>
            <p class="text-slate-500 text-xs font-semibold mt-0.5">Dinas Kepemudaan dan Olahraga Kota Palembang</p>
        </div>

        {{-- Main Form Card --}}
        <div class="auth-card rounded-3xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <p class="text-center text-xs font-semibold text-slate-400 mt-6">
            © {{ date('Y') }} SIPANDA — Dispora Kota Palembang
        </p>
    </div>
</body>
</html>



