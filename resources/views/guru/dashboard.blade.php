@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Selamat datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
    <p class="text-gray-500 mt-1">Berikut ringkasan aktivitas bimbingan tahfidz Anda hari ini.</p>
</div>

{{-- Highlight Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="card p-6 flex items-center gap-4 bg-gradient-to-br from-teal-500 to-teal-700 text-white border-none shadow-lg shadow-teal-500/20">
        <div class="p-3 bg-white/20 rounded-xl">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-teal-100 text-sm font-medium">Siswa Bimbingan</p>
            <h3 class="text-3xl font-black mt-0.5">{{ $totalSiswa }}</h3>
        </div>
    </div>
    
    <div class="card p-6 flex items-center gap-4 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Total Hafalan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ $totalHafalan }}</h3>
        </div>
    </div>
    
    <div class="card p-6 flex items-center gap-4 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Setoran Hari Ini</p>
            <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ $hariIni }}</h3>
        </div>
    </div>
    
    <div class="card p-6 flex items-center gap-4 border-l-4 border-l-amber-500 transition hover:-translate-y-1 hover:shadow-md">
        <div class="p-3 bg-amber-50 text-amber-500 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-gray-500 text-sm font-medium">Perlu Dievaluasi</p>
            <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ $perluDievaluasi }}</h3>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Aktivitas Setoran Terbaru --}}
    <div class="lg:col-span-2">
        <div class="card p-0 overflow-hidden h-full flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Setoran Terbaru</h2>
                <a href="{{ route('guru.hafalan.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-700 hover:underline">Lihat Semua</a>
            </div>
            
            <div class="p-0 overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 font-semibold">Siswa</th>
                            <th class="py-4 px-6 font-semibold">Surah & Ayat</th>
                            <th class="py-4 px-6 font-semibold text-center">Tgl Input</th>
                            <th class="py-4 px-6 font-semibold text-center">Status Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($hafalanTerbaru as $hafalan)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-6 whitespace-nowrap">
                                    <div class="font-bold text-gray-800">{{ $hafalan->siswa->user->nama_lengkap ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5 font-medium">NISN: {{ $hafalan->siswa->nisn ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-6 whitespace-nowrap">
                                    <div class="font-bold text-teal-700">{{ $hafalan->nama_surah }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Total: {{ $hafalan->jumlah_ayat }} Ayat</div>
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap text-xs text-gray-500 font-medium">
                                    {{ \Carbon\Carbon::parse($hafalan->tanggal_input)->diffForHumans() }}
                                </td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    @if($hafalan->nilaiEvaluasi)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Dinilai
                                        </span>
                                    @else
                                        <a href="{{ route('guru.hafalan.show', $hafalan->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 hover:text-amber-800 transition shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Beri Nilai
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Belum ada setoran hafalan terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Aksi Cepat Cerdas --}}
    <div class="lg:col-span-1 border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Tindakan Cepat
        </h2>

        <div class="flex flex-col gap-3">
            <a href="{{ route('guru.hafalan.create') }}" class="group relative overflow-hidden bg-white p-4 rounded-xl border border-indigo-100 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-indigo-50 to-transparent -z-10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Catat Hafalan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Input setoran hafalan siswa</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('guru.siswa.create') }}" class="group relative overflow-hidden bg-white p-4 rounded-xl border border-teal-100 shadow-sm hover:shadow-md hover:border-teal-300 transition-all">
                <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-teal-50 to-transparent -z-10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Tambah Siswa</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftarkan siswa baru bimbingan</p>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('guru.laporan.create') }}" class="group relative overflow-hidden bg-white p-4 rounded-xl border border-amber-100 shadow-sm hover:shadow-md hover:border-amber-300 transition-all">
                <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-amber-50 to-transparent -z-10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Cetak Laporan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Generate PDF pencapaian</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection
