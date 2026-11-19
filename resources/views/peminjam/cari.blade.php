<x-app-layout>
    <x-slot name="title">Cari Aset</x-slot>

    @push('styles')
    <style>
        .search-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 50%, #0ea5e9 100%);
            position: relative;
            overflow: hidden;
        }
        .search-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .search-box {
            background: white;
            border-radius: 50px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
            transition: box-shadow 0.2s;
        }
        .search-box:focus-within {
            box-shadow: 0 8px 40px rgba(0,0,0,0.25);
        }
        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
            color: #1e293b;
        }
        .search-input::placeholder { color: #94a3b8; }

        .kategori-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            border: 1.5px solid rgba(255,255,255,0.3);
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.1);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }
        .kategori-chip:hover,
        .kategori-chip.active {
            background: white;
            color: #1d4ed8;
            border-color: white;
        }

        .aset-card {
            background: white;
            border-radius: 16px;
            border: 1.5px solid #f1f5f9;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
        }
        .aset-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 8px 30px rgba(59,130,246,0.12);
            transform: translateY(-2px);
        }

        .kondisi-layak { background: #dcfce7; color: #166534; }
        .status-tersedia { background: #dbeafe; color: #1e40af; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.4s ease both; }
        .fade-in-up:nth-child(2) { animation-delay: 0.05s; }
        .fade-in-up:nth-child(3) { animation-delay: 0.1s; }
        .fade-in-up:nth-child(4) { animation-delay: 0.15s; }
        .fade-in-up:nth-child(5) { animation-delay: 0.2s; }
        .fade-in-up:nth-child(6) { animation-delay: 0.25s; }
    </style>
    @endpush

    {{-- ================================================================
         HERO SEARCH SECTION
    ================================================================ --}}
    <div class="search-hero -mx-6 -mt-4 px-6 pt-12 pb-10 mb-8 relative">
        {{-- Greeting --}}
        <div class="text-center mb-8 relative z-10">
            <p class="text-blue-200 text-sm font-medium tracking-wide uppercase mb-1">SIPANDA</p>
            <h1 class="text-3xl font-bold text-white mb-1">
                Cari Aset yang Ingin Dipinjam
            </h1>
            <p class="text-blue-200 text-sm">
                Halo, <strong>{{ Str::words(Auth::user()->name, 1, '') }}</strong> — temukan aset tersedia milik Dispora Palembang
            </p>
        </div>

        {{-- Search Bar --}}
        <div class="relative z-10 max-w-2xl mx-auto">
            <form method="GET" action="{{ route('peminjam.cari') }}" id="search-form">
                <div class="search-box flex items-center px-5 py-3.5 gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        id="search-input"
                        name="q"
                        type="text"
                        class="search-input flex-1"
                        placeholder="Cari nama aset, kategori, atau keperluan..."
                        value="{{ $query ?? '' }}"
                        autocomplete="off"
                        autofocus
                    >
                    @if($query || $kategoriFilter)
                        <a href="{{ route('peminjam.cari') }}" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                    <button type="submit"
                            class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-full transition">
                        Cari
                    </button>
                    @if($kategoriFilter)
                        <input type="hidden" name="kategori" value="{{ $kategoriFilter }}">
                    @endif
                </div>
            </form>
        </div>
    </div>


    {{-- ================================================================
         CLUSTER KATEGORI FILTER CHIPS
    ================================================================ --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none">
        <a href="{{ route('peminjam.cari', array_merge(request()->except('kategori', 'page'))) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('kategori') ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua Aset ({{ $kategoris->sum('asets_count') }})
        </a>
        @foreach($kategoris as $k)
            <a href="{{ route('peminjam.cari', array_merge(request()->except('page'), ['kategori' => $k->id])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('kategori') == $k->id ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $k->nama }} ({{ $k->asets_count }})
            </a>
        @endforeach
    </div>

    {{-- ================================================================
         HASIL PENCARIAN / KATALOG ASET TERSEDIA
    ================================================================ --}}
    @if($asets !== null)

        {{-- Ada hasil --}}
        @if($asets->count() > 0)
            <div class="mb-4 flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Menampilkan <strong class="text-slate-800">{{ $asets->total() }}</strong> aset siap dipinjam
                    @if($query) untuk "<strong class="text-blue-600">{{ $query }}</strong>"@endif
                </p>
                <span class="text-xs text-slate-400">{{ $asets->currentPage() }} / {{ $asets->lastPage() }} halaman</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($asets as $aset)
                    <div class="aset-card fade-in-up" onclick="window.location='{{ route('peminjam.peminjaman.create') }}?aset_id={{ $aset->id }}'">
                        {{-- Foto Aset --}}
                        <div class="h-40 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center relative overflow-hidden">
                            @if($aset->foto)
                                <img src="{{ Storage::url($aset->foto) }}" alt="{{ $aset->nama_aset }}"
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                            {{-- Kategori badge --}}
                            <span class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-xs font-semibold text-slate-700 px-2.5 py-1 rounded-lg border border-slate-100 shadow-sm">
                                {{ $aset->kategori->nama ?? '-' }}
                            </span>
                        </div>

                        {{-- Info --}}
                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-sm leading-snug mb-1">{{ $aset->nama_aset }}</h3>
                            <p class="text-xs text-slate-400 mb-3">
                                {{ $aset->merk ?? '' }}{{ ($aset->merk && $aset->tahun_pengadaan) ? ' · ' : '' }}{{ $aset->tahun_pengadaan ?? '' }}
                                {{ (!$aset->merk && !$aset->tahun_pengadaan) ? $aset->lokasi ?? 'Dispora Palembang' : '' }}
                            </p>

                            <div class="flex items-center justify-between gap-2 mb-3">
                                @if($aset->stok_tersedia <= 0)
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                                        ✕ Stok Kosong
                                    </span>
                                @endif
                                <span class="text-[11px] font-mono text-slate-400">{{ $aset->kode_aset }}</span>
                            </div>

                            @if($aset->stok_tersedia > 0)
                                <a href="{{ route('peminjam.peminjaman.create') }}?aset_id={{ $aset->id }}"
                                   class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm"
                                   onclick="event.stopPropagation()">
                                    Pinjam Aset Ini →
                                </a>
                            @else
                                <button disabled
                                   class="block w-full text-center py-2 bg-slate-100 text-slate-400 text-xs font-medium rounded-xl cursor-not-allowed">
                                    Sedang Dipinjam Penuh
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($asets->hasPages())
                <div class="mt-6">{{ $asets->withQueryString()->links() }}</div>
            @endif

        @else
            {{-- Tidak ada hasil --}}
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="font-semibold text-gray-600 text-lg mb-2">Aset tidak ditemukan</h3>
                <p class="text-gray-400 text-sm mb-4">
                    Tidak ada aset tersedia yang cocok dengan "<strong>{{ $query }}</strong>"
                </p>
                <a href="{{ route('peminjam.cari') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                    Lihat Semua Aset
                </a>
            </div>
        @endif

    @else
        {{-- Quick Info --}}

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex gap-4 items-start">
                <div class="text-2xl">📋</div>
                <div>
                    <p class="font-semibold text-blue-800 text-sm">Cara Meminjam</p>
                    <p class="text-xs text-blue-600 mt-1 leading-relaxed">Cari aset → Isi formulir → Tunggu verifikasi → Ambil aset</p>
                </div>
            </div>
            <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5 flex gap-4 items-start">
                <div class="text-2xl">✅</div>
                <div>
                    <p class="font-semibold text-yellow-800 text-sm">Aset Maintenance</p>
                    <p class="text-xs text-yellow-600 mt-1 leading-relaxed">Jika aset dalam maintenance, laporkan keluhan untuk perbaikan</p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-2xl p-5 flex gap-4 items-start">
                <div class="text-2xl">✅</div>
                <div>
                    <p class="font-semibold text-green-800 text-sm">Hanya Aset Layak</p>
                    <p class="text-xs text-green-600 mt-1 leading-relaxed">Semua aset di sini sudah diverifikasi kondisinya via Decision Tree</p>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Enter key triggers search immediately
        document.getElementById('search-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('search-form').submit();
            }
        });
    </script>
</x-app-layout>
