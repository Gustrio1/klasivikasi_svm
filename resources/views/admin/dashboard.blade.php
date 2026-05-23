@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Selamat datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
    <p class="text-gray-500 mt-1">Pantau seluruh aktivitas sistem hafalan Al-Qur'an secara menyeluruh.</p>
</div>

{{-- ── Highlight Cards ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card p-6 flex items-center gap-4 bg-gradient-to-br from-teal-500 to-teal-700 text-white border-none shadow-lg shadow-teal-500/20">
        <div class="p-3 bg-white/20 rounded-xl">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zM7 10a2 2 0 11-4 0 2 2 0 014 0zM17 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-teal-100 text-sm font-medium">Total User</p>
            <h3 class="text-3xl font-black mt-0.5">{{ $totalUser }}</h3>
        </div>
    </div>
    <div class="card p-6 flex items-center gap-4 border-l-4 border-l-indigo-500 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Guru</p>
            <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ $totalGuru }}</h3>
        </div>
    </div>
    <div class="card p-6 flex items-center gap-4 border-l-4 border-l-emerald-500 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
            <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ $totalSiswa }}</h3>
        </div>
    </div>
    <div class="card p-6 flex items-center gap-4 border-l-4 border-l-amber-500 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-amber-50 text-amber-500 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Hafalan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ $totalHafalan }}</h3>
        </div>
    </div>
</div>

{{-- ── Model SVM Aktif + Quick Access ──────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    {{-- Model SVM Banner --}}
    <div class="lg:col-span-2 card p-6 bg-gradient-to-r from-slate-800 to-slate-900 border-none text-white">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Model SVM Aktif</p>
                <h3 class="text-xl font-black">{{ $modelAktif?->versi_model ?? 'Belum Ada Model Aktif' }}</h3>
                <p class="text-slate-400 text-sm mt-1">Kernel: {{ strtoupper($modelAktif?->kernel_type ?? '-') }} · C: {{ $modelAktif?->parameter_C ?? '-' }} · γ: {{ $modelAktif?->parameter_gamma ?? '-' }}</p>
            </div>
            <div class="p-3 bg-indigo-500/20 rounded-xl">
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
        </div>
        @if($modelAktif)
            <div class="flex items-center gap-3 mt-3">
                <div class="flex-1 bg-slate-700 rounded-full h-2">
                    <div class="h-2 bg-indigo-500 rounded-full" style="width: {{ $modelAktif->akurasi_model * 100 }}%"></div>
                </div>
                <span class="text-sm font-bold text-indigo-400">{{ number_format($modelAktif->akurasi_model * 100, 1) }}% Akurasi</span>
            </div>
        @endif
        <div class="flex gap-3 mt-5">
            <a href="{{ route('admin.model-svm.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">Kelola Model SVM</a>
            <a href="{{ route('admin.hasil-klasifikasi.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-lg transition">Lihat Semua Klasifikasi</a>
        </div>
    </div>

    {{-- Quick Access Admin --}}
    <div class="card p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Akses Cepat
        </h2>
        <div class="space-y-2">
            @php
            $quickLinks = [
                ['label' => 'Manajemen Siswa',    'url' => route('admin.siswa.index'),          'color' => 'emerald'],
                ['label' => 'Manajemen Guru',     'url' => route('admin.guru.index'),           'color' => 'indigo'],
                ['label' => 'Data Training',      'url' => route('admin.data-training.index'),  'color' => 'purple'],
                ['label' => 'Media Hafalan',      'url' => route('admin.media-hafalan.index'),  'color' => 'teal'],
                ['label' => 'Laporan PDF',        'url' => route('admin.laporan.index'),        'color' => 'amber'],
                ['label' => 'Hasil Klasifikasi',  'url' => route('admin.hasil-klasifikasi.index'), 'color' => 'red'],
            ];
            @endphp
            @foreach($quickLinks as $link)
            <a href="{{ $link['url'] }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border border-gray-100 hover:border-{{ $link['color'] }}-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-{{ $link['color'] }}-700">{{ $link['label'] }}</span>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-{{ $link['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Akses Fitur Siswa & Guru ─────────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Fitur Guru --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">Fitur Guru</h3>
                <p class="text-xs text-gray-400">Akses penuh seperti Guru</p>
            </div>
        </div>
        <div class="space-y-2">
            <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 border border-gray-100 hover:border-indigo-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">👨‍🎓 Data Siswa</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.hasil-klasifikasi.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 border border-gray-100 hover:border-indigo-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">📊 Hasil Klasifikasi SVM</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 border border-gray-100 hover:border-indigo-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">📄 Laporan PDF Siswa</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.media-hafalan.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-50 border border-gray-100 hover:border-indigo-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">🎬 Media Hafalan</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Fitur Siswa --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-4">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-800">Fitur Siswa</h3>
                <p class="text-xs text-gray-400">Pantau perkembangan seperti Siswa</p>
            </div>
        </div>
        <div class="space-y-2">
            <a href="{{ route('admin.hasil-klasifikasi.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-teal-50 border border-gray-100 hover:border-teal-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">🏆 Riwayat Klasifikasi</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-teal-50 border border-gray-100 hover:border-teal-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">📑 Laporan Perkembangan</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.media-hafalan.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-teal-50 border border-gray-100 hover:border-teal-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">🎬 Media Pembelajaran</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.model-svm.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-teal-50 border border-gray-100 hover:border-teal-200 transition group">
                <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">🤖 Status Model SVM</span>
                <svg class="w-4 h-4 text-gray-300 ml-auto group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

@endsection
