<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-500 text-xs font-medium mt-1">Silakan masuk untuk mengakses portal SIPANDA</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('email') border-rose-400 bg-rose-50/30 @enderror"
                   placeholder="nama@domain.com">
            @error('email') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lupa password?</a>
                @endif
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition bg-slate-50/50 focus:bg-white @error('password') border-rose-400 bg-rose-50/30 @enderror"
                   placeholder="••••••••">
            @error('password') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between py-1">
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input id="remember_me" name="remember" type="checkbox"
                       class="w-4 h-4 text-blue-600 border-slate-300 rounded-md focus:ring-blue-500/20">
                <span class="text-xs font-medium text-slate-600">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-blue-500/25 active:scale-[0.99]">
            Masuk Sekarang
        </button>
    </form>

    @if (Route::has('register'))
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-xs font-medium text-slate-500">
                Belum memiliki akun peminjam?
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline ml-1">Daftar Akun Baru</a>
            </p>
        </div>
    @endif
</x-guest-layout>

