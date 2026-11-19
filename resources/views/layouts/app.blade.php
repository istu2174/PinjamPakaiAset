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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #94a3b8;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
            color: #f8fafc;
            transform: translateX(2px);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="h-full bg-slate-50/70 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

<div class="flex h-full min-h-screen">

    {{-- ================================================================
         SIDEBAR
    ================================================================ --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-64 sidebar-gradient text-white flex flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full shadow-xl border-r border-slate-800/60"
           aria-label="Sidebar">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800/80">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 8v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2"/>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-white text-base tracking-wide leading-none">SIPANDA</p>
                <p class="text-slate-400 text-[11px] font-medium mt-1">Dispora Kota Palembang</p>
            </div>
        </div>

        {{-- User Profile Card --}}
        <div class="px-4 py-4 border-b border-slate-800/80">
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-800/50 border border-slate-700/50">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-blue-500 to-cyan-400 flex items-center justify-center text-xs font-bold text-white shadow-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-white text-xs font-semibold truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-block text-[10px] font-medium px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/20 capitalize mt-0.5">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1 custom-scrollbar">

            @if(Auth::user()->role === 'admin')
                {{-- Admin Nav --}}
                <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 tracking-wider uppercase">Menu Admin</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Manajemen User
                </a>

                <a href="{{ route('admin.aset.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.aset.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Manajemen Aset
                </a>

                <a href="{{ route('admin.peminjaman.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Peminjaman
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan & Export
                </a>

                <a href="{{ route('simulasi.algoritma') }}"
                   class="sidebar-link {{ request()->routeIs('simulasi.algoritma') ? 'active' : '' }}">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Simulasi Algoritma
                </a>

            @elseif(Auth::user()->role === 'pengelola')
                {{-- Pengelola Nav --}}
                <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 tracking-wider uppercase">Menu Pengelola</p>

                <a href="{{ route('pengelola.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('pengelola.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('pengelola.peminjaman.index') }}"
                   class="sidebar-link {{ request()->routeIs('pengelola.peminjaman.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2l-3 10H9l-3-10"/></svg>
                    Pengajuan Pinjaman
                </a>

                <a href="{{ route('pengelola.aset.index') }}"
                   class="sidebar-link {{ request()->routeIs('pengelola.aset.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Penilaian Aset (DT)
                </a>

                <a href="{{ route('pengelola.keluhan.index') }}"
                   class="sidebar-link {{ request()->routeIs('pengelola.keluhan.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Keluhan
                    @php $keluhan_pending = \App\Models\Keluhan::pending()->count(); @endphp
                    @if($keluhan_pending > 0)
                        <span class="ml-auto bg-rose-500 text-white text-[10px] font-bold rounded-full h-5 px-1.5 flex items-center justify-center shadow-sm">{{ $keluhan_pending }}</span>
                    @endif
                </a>

                <a href="{{ route('simulasi.algoritma') }}"
                   class="sidebar-link {{ request()->routeIs('simulasi.algoritma') ? 'active' : '' }}">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Simulasi Algoritma
                </a>

            @elseif(Auth::user()->role === 'peminjam')
                {{-- Peminjam Nav --}}
                <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 tracking-wider uppercase">Menu Peminjam</p>

                {{-- Cari Aset (halaman utama) --}}
                <a href="{{ route('peminjam.cari') }}"
                   class="sidebar-link {{ request()->routeIs('peminjam.cari') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari Aset
                    <span class="ml-auto text-[10px] bg-blue-500/20 text-blue-300 font-semibold px-2 py-0.5 rounded-full border border-blue-400/30">Utama</span>
                </a>

                <a href="{{ route('peminjam.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('peminjam.peminjaman.create') }}"
                   class="sidebar-link {{ request()->routeIs('peminjam.peminjaman.create') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajukan Pinjaman
                </a>

                <a href="{{ route('peminjam.peminjaman.index') }}"
                   class="sidebar-link {{ request()->routeIs('peminjam.peminjaman.index') || request()->routeIs('peminjam.peminjaman.show') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Riwayat Pinjaman
                </a>

                <a href="{{ route('peminjam.keluhan.index') }}"
                   class="sidebar-link {{ request()->routeIs('peminjam.keluhan.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Keluhan
                </a>

                <a href="{{ route('simulasi.algoritma') }}"
                   class="sidebar-link {{ request()->routeIs('simulasi.algoritma') ? 'active' : '' }}">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Simulasi Algoritma
                </a>
            @endif

            {{-- Shared Section --}}
            <div class="pt-4 border-t border-slate-800/80 mt-4">
                <p class="px-3 py-1.5 text-[10px] font-bold text-slate-400 tracking-wider uppercase">Akun & Sistem</p>
                <a href="{{ route('notifikasi.index') }}"
                   class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifikasi
                    <span id="notif-badge" class="ml-auto bg-rose-500 text-white text-[10px] font-bold rounded-full h-5 px-1.5 items-center justify-center hidden shadow-sm">0</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>
            </div>
        </nav>

        {{-- Logout Button --}}
        <div class="p-4 border-t border-slate-800/80">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all text-xs font-semibold group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-rose-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar Overlay (mobile) --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"
         onclick="closeSidebar()"></div>

    {{-- ================================================================
         MAIN CONTENT AREA
    ================================================================ --}}
    <div class="flex-1 flex flex-col min-h-screen lg:pl-64">

        {{-- Top Bar Header --}}
        <header class="sticky top-0 z-30 glass-header border-b border-slate-200/80 px-6 py-3.5 flex items-center gap-4 shadow-sm/50">
            {{-- Mobile menu toggle --}}
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100/80 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title / Breadcrumb --}}
            <div class="flex-1">
                <h1 class="text-base font-bold text-slate-800 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
                @isset($breadcrumb)
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $breadcrumb }}</p>
                @endisset
            </div>

            {{-- Right Items --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('notifikasi.index') }}" class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100/80 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span id="notif-badge-top"
                          class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center hidden shadow-sm">
                    </span>
                </a>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl mb-4 text-xs font-semibold text-emerald-800 shadow-sm transition-all" id="flash-success">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-start gap-3 p-4 bg-rose-50 border border-rose-200/80 rounded-2xl mb-4 text-xs font-semibold text-rose-800 shadow-sm transition-all" id="flash-error">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        {{-- Main Content --}}
        <main class="flex-1 px-6 py-6 pb-12">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-slate-200/60 text-center text-xs font-medium text-slate-400">
            © {{ date('Y') }} <span class="font-semibold text-slate-600">SIPANDA</span> — Dinas Kepemudaan dan Olahraga Kota Palembang
        </footer>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }

    // Auto-dismiss flash messages
    setTimeout(() => {
        ['flash-success','flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        });
    }, 5000);

    // Fetch unread notif count
    async function fetchNotifCount() {
        try {
            const r = await fetch('{{ route('notifikasi.unread-count') }}');
            const d = await r.json();
            ['notif-badge','notif-badge-top'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                if (d.count > 0) {
                    el.textContent = d.count > 9 ? '9+' : d.count;
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            });
        } catch(e) {}
    }
    fetchNotifCount();
    setInterval(fetchNotifCount, 60000);
</script>
</body>
</html>

