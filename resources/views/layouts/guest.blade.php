<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login') — Sistem Hafalan Qur'an</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-inter h-full">

    {{-- Background gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-[#1D4E3A] via-[#1D9E75] to-[#185FA5] flex items-center justify-center p-4">

        {{-- Card Container --}}
        <div class="w-full max-w-md">

            {{-- Logo & App Name --}}
            <div class="text-center mb-8">
                {{-- Logo placeholder: lingkaran hijau dengan inisial "HQ" --}}
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-xl mb-4">
                    <span class="text-2xl font-bold text-teal-600">HQ</span>
                </div>
                <h1 class="text-2xl font-bold text-white">Sistem Hafalan Qur'an</h1>
                <p class="text-green-200 text-sm mt-1">Klasifikasi SVM untuk evaluasi hafalan</p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                {{ $slot ?? '' }}
                @yield('content')
            </div>

            {{-- Footer text --}}
            <p class="text-center text-green-200 text-xs mt-6">
                © {{ date('Y') }} Sistem Hafalan Qur'an. All rights reserved.
            </p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
