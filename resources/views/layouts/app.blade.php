<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>@yield('title', $pageTitle ?? 'Dashboard') — Sistem Hafalan Qur'an</title>

    {{-- Favicon --}}
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🕌</text></svg>">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Push styles dari child view --}}
    @stack('styles')
</head>

<body class="bg-gray-50 font-inter h-full" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- ── Overlay Mobile ────────────────────────────────── --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

        {{-- ── Sidebar ────────────────────────────────────────── --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out
                   lg:relative lg:translate-x-0 lg:flex lg:flex-shrink-0">
            @include('components.sidebar')
        </aside>

        {{-- ── Content Area ────────────────────────────────────── --}}
        <div class="flex flex-1 flex-col overflow-hidden min-w-0">

            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="p-6">
                    {{-- Alert Flash Messages --}}
                    <x-alert />

                    {{-- Page Content --}}
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            @include('components.footer')

        </div>
    </div>

    {{-- Push scripts dari child view --}}
    @stack('scripts')

</body>

</html>