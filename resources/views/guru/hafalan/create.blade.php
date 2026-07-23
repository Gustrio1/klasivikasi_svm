@extends('layouts.app')

@section('title', 'Input Hafalan Baru')

@section('content')

<x-page-header
    title="Input Hafalan Baru"
    subtitle="Catat progres setoran hafalan siswa beserta nilai indikator pembacaan"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hafalan', 'url' => route('guru.hafalan.index')],
        ['label' => 'Baru', 'url' => null],
    ]"
/>

<div x-data="hafalanForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Left Column: Form Input --}}
    <div class="lg:col-span-2 card p-6 md:p-8">
        <form method="POST" action="{{ route('guru.hafalan.store') }}" id="formHafalan" class="space-y-6">
            @csrf
            
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Setoran</h3>
            
            {{-- Pilih Semester --}}
            <div>
                <label for="periode_semester" class="form-label">Periode Semester <span class="text-red-500">*</span></label>
                <select name="periode_semester" id="periode_semester" class="form-input @error('periode_semester') border-red-500 @enderror" required>
                    <option value="">-- Pilih Periode Semester --</option>
                    @php
                        $currentYear = date('Y');
                        $nextYear = $currentYear + 1;
                        $prevYear = $currentYear - 1;
                        $semesters = [
                            "Ganjil $currentYear/$nextYear",
                            "Genap $prevYear/$currentYear",
                            "Ganjil $prevYear/$currentYear",
                        ];
                    @endphp
                    @foreach($semesters as $sem)
                        <option value="{{ $sem }}" {{ old('periode_semester', request('periode_semester')) == $sem ? 'selected' : '' }}>
                            {{ $sem }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1 italic">Pilih semester agar hasil hafalan bisa diakumulasi di akhir semester.</p>
                @error('periode_semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            {{-- Pilih Siswa --}}
            <div>
                <label for="id_siswa" class="form-label">Pilih Siswa <span class="text-red-500">*</span></label>
                <select name="id_siswa" id="id_siswa" class="form-input @error('id_siswa') border-red-500 @enderror" required>
                    <option value="">-- Silakan Pilih Siswa --</option>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ old('id_siswa', request('id_siswa')) == $s->id ? 'selected' : '' }}>
                            {{ $s->user->nama_lengkap }} (NISN: {{ $s->nisn ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('id_siswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nama Surah --}}
            <div>
                <label for="nama_surah" class="form-label">Nama Surah <span class="text-red-500">*</span></label>
                <select name="nama_surah" id="nama_surah"
                        class="form-input @error('nama_surah') border-red-500 @enderror" required>
                    <option value="">-- Pilih Nama Surah --</option>
                    @foreach($masterSurahs as $ms)
                        <option value="{{ $ms->nama_surah }}"
                            {{ old('nama_surah') == $ms->nama_surah ? 'selected' : '' }}>
                            {{ $ms->nama_surah }} ({{ $ms->jumlah_ayat }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1 italic">Pilih surat yang disetorkan siswa.</p>
                @error('nama_surah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rentang Ayat --}}
            <div>
                <label for="jumlah_ayat" class="form-label">Jumlah Ayat <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_ayat" id="jumlah_ayat" class="form-input @error('jumlah_ayat') border-red-500 @enderror" value="{{ old('jumlah_ayat') }}" required min="1" max="1000" placeholder="Contoh: 5">
                @error('jumlah_ayat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mt-8 mb-4">Metode Pembelajaran</h3>

            {{-- Pilih Media --}}
            <div>
                <label for="id_media" class="form-label">Media yang Digunakan <span class="text-red-500">*</span></label>
                <select name="id_media" id="id_media" class="form-input @error('id_media') border-red-500 @enderror" required>
                    <option value="">-- Pilih Media Hafalan Pendukung --</option>
                    @foreach($medias as $m)
                        <option value="{{ $m->id }}" {{ old('id_media', request('id_media')) == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_media }} ({{ Str::title($m->jenis_media) }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-2 italic">Pemilihan media ini akan menjadi salah satu faktor input perhitungan bagi mesin analitik AI kita.</p>
                @error('id_media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

        </form>
    </div>

    {{-- Right Column: Prediction Preview --}}
    <div class="lg:col-span-1">
        <div class="card p-6 bg-gradient-to-b from-slate-800 to-slate-900 border-none text-white sticky top-24 shadow-xl">
            <div class="text-center mb-6">
                <p class="text-slate-400 text-xs font-bold tracking-widest uppercase mb-2">Analisis AI</p>
                <h3 class="text-xl font-bold text-white mb-1">Klasifikasi Cerdas</h3>
                <p class="text-xs text-slate-400">Dimensi fitur diubah menjadi {Siswa, Surah, Ayat, Media}</p>
            </div>
            
            <div class="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 shadow-inner flex flex-col items-center justify-center min-h-[160px] mb-6 relative overflow-hidden group">
                
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 to-teal-500/10 opacity-50 group-hover:opacity-100 transition duration-500"></div>

                <div class="flex flex-col items-center relative z-10 animate-pulse-slow">
                    <span class="text-indigo-400 hover:text-indigo-300 w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </span>
                    <span class="text-slate-300 font-bold tracking-wide text-sm text-center">Menunggu Data...</span>
                </div>
                
            </div>
            
            <div class="text-xs text-slate-400 bg-slate-800 rounded-lg p-3 border border-slate-700 mb-6 space-y-2">
                <p class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tidak ada simulasi nilai Kelancaran/Makhraj di tahap ini karena parameter telah direvisi total.</span>
                </p>
                <p class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Sistem <strong>Support Vector Machine (SVM)</strong> di server akan menerima koordinat fitur riil saat Anda menekan tombol simpan bawah ini.</span>
                </p>
            </div>

            <button type="submit" form="formHafalan" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold py-3 rounded-xl transition shadow-lg shadow-teal-500/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Setoran Harian
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function hafalanForm() {
        return {}
    }
</script>
<style>
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@endsection
