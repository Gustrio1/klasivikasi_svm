{{-- =========================================================
     Sidebar — resources/views/components/sidebar.blade.php
     Background: #1D4E3A (hijau gelap islami)
     ========================================================= --}}

@php
    $role    = auth()->user()->role ?? 'siswa';
    $current = request()->path();

    if (!function_exists('isActive')) {
        function isActive(string $route): bool {
            try { return request()->routeIs($route . '*'); }
            catch (\Exception $e) { return false; }
        }
    }
@endphp

<div class="h-full flex flex-col bg-[#1D4E3A] w-64 shadow-xl">

    {{-- ── Logo / Header ──────────────────────────────────── --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-teal-400/30 flex items-center justify-center">
            <span class="text-white font-bold text-base">HQ</span>
        </div>
        <div>
            <p class="text-white font-semibold text-sm leading-tight">Hafalan Qur'an</p>
            <p class="text-green-300 text-xs">Sistem Klasifikasi SVM</p>
        </div>
    </div>

    {{-- ── Role Badge ──────────────────────────────────────── --}}
    <div class="px-5 py-3 border-b border-white/10">
        <span class="text-[11px] font-semibold uppercase tracking-widest
            {{ $role === 'admin' ? 'text-blue-300' : ($role === 'guru' ? 'text-green-300' : 'text-yellow-300') }}">
            {{ ucfirst($role) }}
        </span>
    </div>

    {{-- ── Navigation ──────────────────────────────────────── --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">

        {{-- ==================== ADMIN MENU ==================== --}}
        @if($role === 'admin')

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="{{ isActive('dashboard') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Manajemen User (accordion) --}}
            <div x-data="{ open: {{ isActive('admin.guru') || isActive('admin.siswa') || isActive('admin.users') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="nav-item w-full justify-between">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Manajemen User
                    </span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pl-8 mt-0.5 space-y-0.5">
                    <a href="{{ route('admin.guru.index') }}"
                       class="{{ isActive('admin.guru') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Data Guru
                    </a>
                    <a href="{{ route('admin.siswa.index') }}"
                       class="{{ isActive('admin.siswa') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Data Siswa
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ isActive('admin.users') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Semua User
                    </a>
                </div>
            </div>

            {{-- SVM & Model (accordion) --}}
            <div x-data="{ open: {{ isActive('admin.data-training') || isActive('admin.model-svm') || isActive('admin.log-evaluasi') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="nav-item w-full justify-between">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18"/>
                        </svg>
                        SVM & Model
                    </span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="pl-8 mt-0.5 space-y-0.5">
                    <a href="{{ route('admin.data-training.index') }}"
                       class="{{ isActive('admin.data-training') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Data Training
                    </a>
                    <a href="{{ route('admin.model-svm.index') }}"
                       class="{{ isActive('admin.model-svm') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Model SVM
                    </a>
                    <a href="{{ route('admin.log-evaluasi.index') }}"
                       class="{{ isActive('admin.log-evaluasi') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Log Evaluasi
                    </a>
                </div>
            </div>

            {{-- Konten (accordion) --}}
            <div x-data="{ open: {{ isActive('admin.media-hafalan') || isActive('admin.hafalan') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="nav-item w-full justify-between">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Konten & Data
                    </span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="pl-8 mt-0.5 space-y-0.5">
                    <a href="{{ route('admin.media-hafalan.index') }}"
                       class="{{ isActive('admin.media-hafalan') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Media Hafalan
                    </a>
                    <a href="{{ route('admin.hafalan.index') }}"
                       class="{{ isActive('admin.hafalan') ? 'nav-item-active' : 'nav-item' }} text-[13px]">
                        Data Hafalan Siswa
                    </a>
                </div>
            </div>

            {{-- Laporan --}}
            <a href="{{ route('admin.laporan.index') }}"
               class="{{ isActive('admin.laporan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>

            {{-- Hasil Klasifikasi --}}
            <a href="{{ route('admin.hasil-klasifikasi.index') }}"
               class="{{ isActive('admin.hasil-klasifikasi') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Hasil Klasifikasi
            </a>

        {{-- ==================== GURU MENU ==================== --}}
        @elseif($role === 'guru')

            <a href="{{ route('dashboard') }}"
               class="{{ isActive('dashboard') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('guru.siswa.index') }}"
               class="{{ isActive('guru.siswa') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Siswa Saya
            </a>

            <a href="{{ route('guru.hafalan.create') }}"
               class="{{ request()->routeIs('guru.hafalan.create') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Input Hafalan
            </a>

            <a href="{{ route('guru.hafalan.index') }}"
               class="{{ request()->routeIs('guru.hafalan.index') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Riwayat Hafalan
            </a>

            <a href="{{ route('guru.hasil-klasifikasi.index') }}"
               class="{{ isActive('guru.hasil-klasifikasi') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Hasil Klasifikasi
            </a>

            <a href="{{ route('guru.media-hafalan.index') }}"
               class="{{ isActive('guru.media-hafalan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Media Hafalan
            </a>

            <a href="{{ route('guru.laporan.index') }}"
               class="{{ isActive('guru.laporan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>

        {{-- ==================== SISWA MENU ==================== --}}
        @else

            <a href="{{ route('dashboard') }}"
               class="{{ isActive('dashboard') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('siswa.hafalan.index') }}"
               class="{{ isActive('siswa.hafalan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Hafalan Saya
            </a>

            <a href="{{ route('siswa.hasil-klasifikasi.index') }}"
               class="{{ isActive('siswa.hasil-klasifikasi') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Hasil Klasifikasi
            </a>


            <a href="{{ route('siswa.media-hafalan.index') }}"
               class="{{ isActive('siswa.media-hafalan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Media Belajar
            </a>

            <a href="{{ route('siswa.laporan.index') }}"
               class="{{ isActive('siswa.laporan') ? 'nav-item-active' : 'nav-item' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>

        @endif
    </nav>

    {{-- ── User Info (Bottom) ──────────────────────────────── --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-teal-400/40 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->nama_lengkap ?? '-' }}</p>
                <p class="text-green-300 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
    </div>

</div>
