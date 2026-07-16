{{-- =========================================================
Navbar — resources/views/components/navbar.blade.php
========================================================= --}}

@php
    $user = auth()->user();
    $initials = collect(explode(' ', $user->nama_lengkap ?? 'User'))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
    $roleClass = match ($user->role ?? 'siswa') {
        'admin' => 'badge-admin',
        'guru' => 'badge-guru',
        default => 'badge-siswa',
    };

    // Hitung notifikasi belum terkirim untuk user login
    $notifCount = 0;
    $notifRoute = '#';
    
    if ($user->role === 'siswa' && $user->siswa) {
        $notifCount = \App\Models\HasilKlasifikasi::where('id_siswa', $user->siswa->id)
            ->where('notifikasi_terkirim', false)->count();
        $notifRoute = route('siswa.hasil-klasifikasi.index');
    } elseif ($user->role === 'guru' && $user->guru) {
        // $notifCount = \App\Models\HasilKlasifikasi::whereHas('dataHafalan', fn($q) => $q->where('id_guru', $user->guru->id))
        // ->where('notifikasi_terkirim', false)->count();
        $notifRoute = route('guru.hasil-klasifikasi.index');
    } elseif ($user->role === 'admin') {
        $notifCount = \App\Models\HasilKlasifikasi::where('notifikasi_terkirim', false)->count();
        $notifRoute = route('admin.hasil-klasifikasi.index');
    }
@endphp

<header class="sticky top-0 z-10 flex h-16 flex-shrink-0 items-center justify-between
               bg-white border-b border-gray-200 shadow-sm px-4 lg:px-6" x-data="{}">

    {{-- ── Kiri: Hamburger + Judul ──────────────────────────── --}}
    <div class="flex items-center gap-4">

        {{-- Hamburger (mobile) --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Judul Halaman --}}
        <div class="hidden sm:block">
            <h2 class="text-lg font-semibold text-gray-800">
                @yield('title', $pageTitle ?? 'Dashboard')
            </h2>
            <p class="text-xs text-gray-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>

    {{-- ── Kanan: Notifikasi + Profil ──────────────────────── --}}
    <div class="flex items-center gap-3">

        {{-- Badge Notifikasi --}}
        <div class="relative">
            <a href="{{ $notifRoute }}" class="relative block p-2 text-gray-500 hover:text-teal-600 hover:bg-gray-100 rounded-lg transition" title="Lihat Notifikasi/Klasifikasi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                @if($notifCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center
                                     rounded-full bg-red-500 text-[10px] font-bold text-white">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                @endif
            </a>
        </div>

        {{-- Dropdown Profil --}}
        <div class="relative" x-data="{ open: false }" @click.away="open = false">

            {{-- Avatar Button --}}
            <button @click="open = !open" class="flex items-center gap-2.5 rounded-lg pl-2 pr-3 py-1.5
                           hover:bg-gray-100 transition focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center
                            text-white text-sm font-semibold flex-shrink-0">
                    {{ $initials }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-medium text-gray-800 leading-tight">
                        {{ Str::limit($user->nama_lengkap ?? 'User', 20) }}
                    </p>
                    <p class="{{ $roleClass }}">{{ ucfirst($user->role) }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-lg border border-gray-200 z-50 overflow-hidden"
                style="display: none;">

                {{-- User Info Header --}}
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    <span class="{{ $roleClass }} mt-1 inline-block">{{ ucfirst($user->role) }}</span>
                </div>



                {{-- Logout --}}
                <div class="border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</header>