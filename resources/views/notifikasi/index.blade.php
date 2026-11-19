<x-app-layout>
    <x-slot name="title">Notifikasi System</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Pemberitahuan & Reminder</h2>
            <p class="text-xs font-medium text-slate-500 mt-1">Notifikasi otomatis status pengajuan, surat pernyataan, dan pengingat pengembalian aset</p>
        </div>
        <form method="POST" action="{{ route('notifikasi.read-all') }}">
            @csrf
            <button type="submit"
                    class="px-4 py-2.5 text-xs font-bold border border-slate-200 rounded-xl hover:bg-slate-50 transition-all text-slate-700 shadow-sm">
                ✓ Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($notifikasis as $n)
            <div class="bg-white rounded-2xl shadow-sm border {{ !$n->dibaca ? 'border-blue-200 bg-blue-50/20' : 'border-slate-200/80' }} p-5 flex items-start gap-4 transition-all">
                {{-- Icon tipe notifikasi --}}
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 border shadow-sm
                    {{ $n->tipe === 'reminder' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($n->tipe === 'surat' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                    @if($n->tipe === 'reminder')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($n->tipe === 'surat')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    @endif
                </div>

                {{-- Konten notifikasi --}}
                <div class="flex-1 overflow-hidden">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-extrabold text-slate-800 text-sm tracking-tight">{{ $n->judul }}</p>
                        @if(!$n->dibaca)
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>
                        @endif
                    </div>
                    <p class="text-slate-600 text-xs mt-1 leading-relaxed font-medium">{{ $n->pesan }}</p>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mt-2">{{ $n->created_at->diffForHumans() }}</p>
                </div>

                {{-- Tombol tandai baca --}}
                @if(!$n->dibaca)
                    <form method="POST" action="{{ route('notifikasi.mark-read', $n->id) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline whitespace-nowrap">Tandai Baca</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-200/80">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-slate-400 text-xs font-medium">Belum ada notifikasi pemberitahuan saat ini</p>
            </div>
        @endforelse
    </div>

    @if($notifikasis->hasPages())
        <div class="mt-6">{{ $notifikasis->links() }}</div>
    @endif
</x-app-layout>

