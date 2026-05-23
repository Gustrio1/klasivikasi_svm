@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')

@php
    $isAdmin = auth()->user()->role === 'admin';
    $routePrefix = $isAdmin ? 'admin.' : 'guru.';
@endphp

<x-page-header
    title="Detail Laporan"
    subtitle="Informasi dan akses unduhan untuk laporan: {{ $laporan->judul_laporan }}"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan', 'url' => route($routePrefix . 'laporan.index')],
        ['label' => 'Detail', 'url' => null],
    ]"
>
    <a href="{{ route($routePrefix . 'laporan.download', $laporan->id) }}" class="btn-primary flex items-center gap-2" target="_blank">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Unduh PDF
    </a>
</x-page-header>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-12">
    {{-- Info Laporan --}}
    <div class="md:col-span-1 space-y-6">
        <div class="card p-6 border-none shadow-xl bg-white">
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                Metadata Laporan
            </h4>
            
            <div class="space-y-5">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Judul</p>
                    <p class="text-sm font-bold text-gray-800 mt-1">{{ $laporan->judul_laporan }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Periode</p>
                    <p class="text-sm font-medium text-gray-700 mt-1">{{ $laporan->periode }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dicetak Pada</p>
                    <p class="text-sm font-medium text-gray-700 mt-1">{{ \Carbon\Carbon::parse($laporan->tanggal_cetak)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Siswa & Guru --}}
    <div class="md:col-span-2 space-y-6">
        <div class="card p-6 border-none shadow-xl bg-white">
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                Informasi Siswa
            </h4>
            
            <div class="flex items-center gap-5">
                @php
                    $nama = $laporan->siswa->user->nama_lengkap ?? 'N/A';
                    $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
                @endphp
                <div class="w-16 h-16 bg-teal-100 text-teal-700 rounded-2xl flex items-center justify-center text-2xl font-black flex-shrink-0">
                    {{ $initials }}
                </div>
                <div>
                    <p class="text-xl font-black text-gray-800">{{ $nama }}</p>
                    <p class="text-sm text-gray-500 font-mono mt-1">NISN: {{ $laporan->siswa->nisn ?? '-' }}</p>
                    <div class="flex gap-2 mt-3">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded font-bold">Kelas {{ $laporan->siswa->kelas ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <hr class="my-6 border-gray-100">
            
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                Guru Pembimbing
            </h4>
            
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center text-lg font-black flex-shrink-0">
                    {{ strtoupper(substr($laporan->guru->user->nama_lengkap ?? 'G', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $laporan->guru->user->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">NIP: {{ $laporan->guru->nip ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center justify-between">
            <p class="text-sm font-medium text-indigo-700">Dokumen PDF tersimpan di server. Klik tombol untuk membuka atau mengunduh.</p>
            <a href="{{ route($routePrefix . 'laporan.download', $laporan->id) }}" class="flex-shrink-0 ml-4 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200" target="_blank">
                Buka PDF
            </a>
        </div>
    </div>
</div>

@endsection
