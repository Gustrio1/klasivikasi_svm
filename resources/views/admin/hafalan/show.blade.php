@extends('layouts.app')

@section('title', 'Detail Setoran Hafalan')

@section('content')

    <x-page-header title="Detail Setoran Hafalan"
        subtitle="Pantau rincian setoran hafalan dan status evaluasi siswa dalam sistem" :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Hafalan', 'url' => route('admin.hafalan.index')],
            ['label' => 'Detail', 'url' => null],
        ]" />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KIRI: INFO HAFALAN & DETAIL (7 Kolom) --}}
        <div class="lg:col-span-7 flex flex-col gap-6">

            {{-- Card Info Hafalan --}}
            <div class="card p-6 flex flex-col sm:flex-row gap-6 items-start sm:items-center relative overflow-hidden bg-gradient-to-r from-teal-700 to-teal-900 shadow-lg border-none text-white">
                <div class="absolute -right-6 -top-12 opacity-10 blur-xl">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>

                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 border border-white/20">
                    <svg class="w-8 h-8 text-teal-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>

                <div class="flex-1">
                    <p class="text-teal-200 text-xs font-bold uppercase tracking-widest mb-1">
                        {{ \Carbon\Carbon::parse($hafalan->tanggal_input)->translatedFormat('l, d F Y') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-black text-white">{{ $hafalan->nama_surah }}</h2>
                        <span class="px-2.5 py-1 bg-teal-800/80 rounded-lg text-sm border border-teal-600">
                            {{ $hafalan->jumlah_ayat }} Ayat
                        </span>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-teal-100">
                        <div class="flex items-center gap-1.5 hover:text-white transition">
                            <svg class="w-4 h-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <a href="{{ route('admin.siswa.show', $hafalan->siswa->id) }}"
                                class="underline decoration-teal-500/50 underline-offset-4 font-semibold">
                                Siswa: {{ $hafalan->siswa->user->nama_lengkap }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Hafalan & Parameter --}}
            <div class="card p-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Detail & Parameter Hafalan</h3>
                    <span class="bg-teal-100 text-teal-700 px-3 py-1 text-xs font-bold rounded-full border border-teal-200 uppercase tracking-widest">
                        Data Hafalan
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- Jumlah Ayat --}}
                    <div class="bg-teal-50 border border-teal-100 rounded-xl p-5 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-[11px] uppercase font-bold text-teal-500 tracking-wider mb-1">Ayat Dihafal</p>
                        <p class="text-3xl font-black text-teal-700">{{ $hafalan->jumlah_ayat }}</p>
                        <p class="text-xs text-teal-500 mt-1">Ayat</p>
                    </div>

                    {{-- Usia Siswa --}}
                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-[11px] uppercase font-bold text-purple-500 tracking-wider mb-1">Usia Saat Setor</p>
                        @php
                            $usiaSiswa = $hafalan->siswa?->tanggal_lahir
                                ? date('Y') - $hafalan->siswa->tanggal_lahir
                                : null;
                        @endphp
                        <p class="text-3xl font-black text-purple-700">{{ $usiaSiswa ?? '-' }}</p>
                        <p class="text-xs text-purple-500 mt-1">Tahun</p>
                    </div>

                    {{-- Media Belajar --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-[11px] uppercase font-bold text-indigo-500 tracking-wider mb-1">Media</p>
                        <p class="text-base font-black text-indigo-700 truncate w-full px-1" title="{{ $hafalan->media?->nama_media ?? '-' }}">
                            {{ $hafalan->media?->nama_media ?? '-' }}
                        </p>
                        @if($hafalan->media?->jenis_media)
                            <span class="text-[10px] uppercase font-semibold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full mt-1">
                                {{ $hafalan->media->jenis_media }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Catatan Evaluasi Guru --}}
                @if($hafalan->nilaiEvaluasi)
                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <h4 class="text-sm font-bold text-gray-800 mb-3">Hasil Evaluasi Guru</h4>
                        @if($hafalan->nilaiEvaluasi->catatan_guru)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Catatan Bimbingan Guru:</p>
                                <p class="text-sm text-gray-700 italic">"{{ $hafalan->nilaiEvaluasi->catatan_guru }}"</p>
                            </div>
                        @else
                            <div class="py-4 text-center text-gray-400 text-sm">
                                Belum ada catatan evaluasi dari guru.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-6 border-t border-gray-100 pt-5 text-center py-6">
                        <span class="text-4xl">📝</span>
                        <p class="text-sm text-gray-500 font-semibold mt-2">Hafalan belum dievaluasi oleh Guru</p>
                        <p class="text-xs text-gray-400 mt-1">Guru pengampu siswa ini belum memberikan catatan bimbingan evaluasi.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- KANAN: KLASIFIKASI SVM SEMESTER (5 Kolom) --}}
        <div class="lg:col-span-5 flex flex-col gap-6">
            <div class="card p-0 overflow-hidden shadow-sm">
                <div class="bg-indigo-700 p-5 text-white flex items-center justify-between">
                    <div>
                        <h3 class="font-bold">Evaluasi Akhir Semester (SVM)</h3>
                        <p class="text-xs text-indigo-300">Klasifikasi kelulusan akumulatif per semester</p>
                    </div>
                    <div class="p-2 bg-indigo-600 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $siswaHafalan = $hafalan->siswa;
                        $hasilSemester = $siswaHafalan?->hasilKlasifikasis()
                            ->where('periode_semester', $hafalan->periode_semester)
                            ->latest('tanggal_klasifikasi')->first();
                    @endphp

                    <p class="text-sm text-gray-600 mb-4">
                        Setoran ini tercatat pada semester <strong>{{ $hafalan->periode_semester ?? '-' }}</strong>.
                    </p>

                    @if($hasilSemester)
                        <div class="p-4 rounded-xl {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }} text-center mb-4">
                            <p class="text-xs uppercase font-bold {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'text-emerald-500' : 'text-red-500' }} mb-1">
                                Prediksi SVM Semester {{ $hafalan->periode_semester }}
                            </p>
                            <p class="text-2xl font-black {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'text-emerald-700' : 'text-red-700' }}">
                                {{ $hasilSemester->kelas_prediksi }}
                            </p>
                            <div class="flex items-center justify-center gap-3 text-xs text-gray-500 mt-2 border-t border-gray-100 pt-2">
                                <span>Surah: <strong>{{ $hasilSemester->total_surah }}</strong></span>
                                <span>&bull;</span>
                                <span>Confidence: <strong>{{ number_format($hasilSemester->confidence_score * 100, 1) }}%</strong></span>
                            </div>
                        </div>
                    @else
                        <div class="py-6 text-center text-gray-400 text-sm border border-dashed border-gray-200 rounded-xl mb-4 bg-gray-50/50">
                            <span class="text-3xl block mb-1">⏳</span>
                            Belum ada evaluasi SVM semester untuk siswa ini.
                        </div>
                    @endif

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-800 flex gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <span class="font-bold">Informasi:</span>
                            <p class="mt-1">Proses evaluasi semester dan kalkulasi SVM hanya dapat dilakukan oleh <strong>Guru Pengampu</strong> siswa melalui halaman pengelolaan siswa bimbingannya.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.hafalan.index') }}" class="btn-secondary w-full text-center">
                Kembali ke Daftar Hafalan
            </a>
        </div>

    </div>

@endsection
