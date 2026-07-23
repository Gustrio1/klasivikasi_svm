@extends('layouts.app')

@section('title', 'Detail Hafalan')

@section('content')

    <x-page-header title="Detail Setoran Hafalan" subtitle="{{ $hafalan->nama_surah }} ({{ $hafalan->jumlah_ayat }} Ayat)" :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Hafalan Saya', 'url' => route('siswa.hafalan.index')],
            ['label' => $hafalan->nama_surah, 'url' => null],
        ]">
        <a href="{{ route('siswa.hafalan.index') }}" class="btn-secondary">← Kembali</a>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- ══ Kolom Kiri ══════════════════════════════════════ --}}
        <div class="space-y-5">

            {{-- Card Info Hafalan --}}
            <div class="card">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    Informasi Hafalan
                </h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500">Nama Surah</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $hafalan->nama_surah }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500">Jumlah Ayat</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $ayatLabel }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500">Tanggal Input</dt>
                        <dd class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($hafalan->tanggal_input)->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500">Dinilai oleh</dt>
                        <dd class="text-sm font-medium text-gray-800">
                            {{ $hafalan->guru?->user?->nama_lengkap ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500">Nilai Kelancaran</dt>
                        <dd class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-teal-500 h-2 rounded-full transition-all"
                                    style="width: {{ $hafalan->nilai_kelancaran * 100 }}%"></div>
                            </div>
                            <span
                                class="text-sm font-semibold text-teal-700">{{ number_format($hafalan->nilai_kelancaran * 100, 1) }}%</span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-sm text-gray-500">Nilai Makhraj</dt>
                        <dd class="flex items-center gap-2">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all"
                                    style="width: {{ $hafalan->nilai_makhraj * 100 }}%"></div>
                            </div>
                            <span
                                class="text-sm font-semibold text-indigo-700">{{ number_format($hafalan->nilai_makhraj * 100, 1) }}%</span>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Card Catatan Evaluasi --}}
            <div class="card">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    Catatan Evaluasi Guru
                </h3>

                @if($hafalan->nilaiEvaluasi && $hafalan->nilaiEvaluasi->catatan_guru)
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-600 font-semibold uppercase tracking-wide mb-1.5">💬 Bimbingan Guru</p>
                        <p class="text-sm text-amber-800 italic">"{{ $hafalan->nilaiEvaluasi->catatan_guru }}"</p>
                    </div>
                @else
                    <div class="py-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Belum ada catatan evaluasi dari guru</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- ══ Kolom Kanan ══════════════════════════════════════ --}}
        <div class="space-y-5">

            {{-- Card Info Evaluasi Semester --}}
            <div class="card bg-indigo-50/50 border-indigo-100">
                <h3 class="text-base font-semibold text-indigo-800 mb-4 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    Evaluasi Semester (SVM)
                </h3>
                <p class="text-sm text-indigo-700 mb-4">
                    Klasifikasi kelulusan kini dihitung berdasarkan <strong>akumulasi total surat</strong> dalam satu semester, bukan per setoran harian.
                    Syarat lulus: <strong>≥ 30 surat</strong> per semester.
                </p>
                @php
                    $hasilSemester = $hafalan->siswa?->hasilKlasifikasis()
                        ->where('periode_semester', $hafalan->periode_semester)
                        ->latest('tanggal_klasifikasi')->first();
                @endphp
                @if($hasilSemester)
                    <div class="p-3 rounded-xl {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'bg-emerald-100' : 'bg-red-100' }} text-center">
                        <p class="text-xs uppercase tracking-widest font-bold {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'text-emerald-600' : 'text-red-600' }} mb-1">
                            Semester {{ $hafalan->periode_semester }}
                        </p>
                        <p class="text-2xl font-black {{ $hasilSemester->kelas_prediksi === 'Lulus' ? 'text-emerald-700' : 'text-red-700' }}">
                            {{ $hasilSemester->kelas_prediksi }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Score: {{ number_format($hasilSemester->confidence_score * 100, 1) }}%</p>
                    </div>
                @else
                    <div class="py-4 text-center text-indigo-400 text-sm">
                        Belum ada evaluasi untuk semester ini. Hubungi guru Anda.
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection