@extends('layouts.app')

@section('title', 'Edit Data Hafalan')

@section('content')

<x-page-header
    title="Edit Data Hafalan"
    subtitle="Ubah detail pencatatan hafalan siswa"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hafalan', 'url' => route('guru.hafalan.index')],
        ['label' => 'Edit', 'url' => null],
    ]"
/>

@php
    $hasSemesterResult = $dataHafalan->siswa?->hasilKlasifikasis()
        ->where('periode_semester', $dataHafalan->periode_semester)
        ->exists();
@endphp
@if($hasSemesterResult)
<div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 text-amber-800 shadow-sm">
    <svg class="w-6 h-6 flex-shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    <div>
        <h4 class="font-bold text-amber-900">Perhatian: Semester ini sudah pernah dievaluasi SVM</h4>
        <p class="text-sm mt-1">Mengubah data hafalan pada semester <strong>{{ $dataHafalan->periode_semester }}</strong> dapat memengaruhi hasil evaluasi SVM. Jika perlu evaluasi ulang, gunakan tab Evaluasi Semester di halaman profil siswa.</p>
    </div>
</div>
@endif


<div x-data="hafalanEditForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Left Column: Form Edit --}}
    <div class="lg:col-span-2 card p-6 md:p-8">
        <form method="POST" action="{{ route('guru.hafalan.update', $dataHafalan->id) }}" id="formHafalanEdit" class="space-y-6">
            @csrf
            @method('PUT')
            
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
                        // If current value is not in array, add it so we don't lose it
                        if ($dataHafalan->periode_semester && !in_array($dataHafalan->periode_semester, $semesters)) {
                            array_unshift($semesters, $dataHafalan->periode_semester);
                        }
                    @endphp
                    @foreach($semesters as $sem)
                        <option value="{{ $sem }}" {{ old('periode_semester', $dataHafalan->periode_semester) == $sem ? 'selected' : '' }}>
                            {{ $sem }}
                        </option>
                    @endforeach
                </select>
                @error('periode_semester') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Pilih Siswa --}}
            <div>
                <label for="id_siswa" class="form-label">Siswa Terpilih <span class="text-red-500">*</span></label>
                <select name="id_siswa" id="id_siswa" class="form-input bg-gray-50 focus:ring-0 @error('id_siswa') border-red-500 @enderror" required>
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ old('id_siswa', $dataHafalan->id_siswa) == $s->id ? 'selected' : '' }}>
                            {{ $s->user->nama_lengkap }} (NISN: {{ $s->nisn ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('id_siswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nama Surah --}}
            <div>
                <label for="nama_surah" class="form-label">Nama Surah <span class="text-red-500">*</span></label>
                <input type="text" list="surah_list" name="nama_surah" id="nama_surah" class="form-input @error('nama_surah') border-red-500 @enderror" value="{{ old('nama_surah', $dataHafalan->nama_surah) }}" required autocomplete="off">
                <datalist id="surah_list">
                    @php
                        $surahs = [
                            'Al-Fatihah','Al-Baqarah','Ali \'Imran','An-Nisa\'','Al-Ma\'idah','Al-An\'am','Al-A\'raf','Al-Anfal','At-Taubah','Yunus', 'Hud','Yusuf','Ar-Ra\'d','Ibrahim','Hijr','An-Nahl','Al-Isra\'','Al-Kahf','Maryam','Ta Ha', 'Al-Anbiya\'','Al-Hajj','Al-Mu\'minun','An-Nur','Al-Furqan','Ash-Shu\'ara\'','An-Naml','Al-Qasas','Al-\'Ankabut', 'Ar-Rum','Luqman','As-Sajdah','Al-Ahzab','Saba\'','Fatir','Yasin','As-Saffat','Sad','Zumar', 'Ghafir','Fussilat','Shura','Az-Zukhruf','Ad-Dukhan','Al-Jathiyah','Al-Ahqaf','Muhammad','Qaf', 'Ad-Dhariyat','At-Tur','An-Najm','Al-Qamar','Ar-Rahman','Al-Waqi\'ah','Al-Hadid','Al-Mujadila','Al-Hashr', 'Al-Mumtahanah','As-Saff','Al-Jumu\'ah','Al-Munafiqun','At-Taghabun','At-Talaq','At-Tahrim','Al-Mulk','Al-Haqqah','Al-Ma\'arij', 'Nuh','Al-Jinn','Al-Muzzammil','Al-Muddaththir','Al-Qiyamah','Al-Insan','Al-Mursalat','An-Naba\'','An-Nazi\'at','Abasa', 'At-Takwir','Al-Infitar','Al-Mutaffifin','Al-Inshiqaq','Al-Buruj','At-Tariq','Al-A\'la','Al-Ghashiyah','Al-Fajr','Al-Balad', 'Ash-Shams','Al-Lail','Ad-Duha','Al-Inshirah','At-Tin','Al-\'Alaq','Al-Qadr','Al-Bayyinah','Al-Zilzal','Al-\'Adiyat', 'Al-Qari\'ah','At-Takathur','Al-\'Asr','Al-Humazah','Al-Fil','Quraish','Al-Ma\'un','Al-Kauthar','Al-Kafirun','An-Nasr', 'Al-Masad','Al-Ikhlas','Al-Falaq','An-Nas'
                        ];
                    @endphp
                    @foreach(array_unique($surahs) as $surah)
                        <option value="{{ $surah }}">
                    @endforeach
                </datalist>
                @error('nama_surah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rentang Ayat --}}
            <div>
                <label for="jumlah_ayat" class="form-label">Jumlah Ayat <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_ayat" id="jumlah_ayat" class="form-input @error('jumlah_ayat') border-red-500 @enderror" value="{{ old('jumlah_ayat', $dataHafalan->jumlah_ayat) }}" required min="1" max="1000">
                @error('jumlah_ayat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mt-8 mb-4">Metode Pembelajaran</h3>

            {{-- Pilih Media --}}
            <div>
                <label for="id_media" class="form-label">Media yang Digunakan <span class="text-red-500">*</span></label>
                <select name="id_media" id="id_media" class="form-input @error('id_media') border-red-500 @enderror" required>
                    <option value="">-- Pilih Media Hafalan Pendukung --</option>
                    @foreach($medias as $m)
                        <option value="{{ $m->id }}" {{ old('id_media', $dataHafalan->id_media) == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_media }} ({{ Str::title($m->jenis_media) }})
                        </option>
                    @endforeach
                </select>
                @error('id_media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Grading components removed --}}

        </form>
    </div>

    {{-- Right Column: Preview --}}
    <div class="lg:col-span-1">
        <div class="card p-6 bg-gradient-to-br from-slate-800 to-slate-900 border-none text-white sticky top-24 shadow-xl">
            <div class="text-center mb-6">
            <div class="text-center mb-6">
                <p class="text-slate-400 text-xs font-bold tracking-widest uppercase mb-2">Analisis AI</p>
                <h3 class="text-xl font-bold text-white mb-1">Klasifikasi Cerdas</h3>
                <p class="text-xs text-slate-400">Dimensi fitur diubah menjadi {Siswa, Surah, Ayat, Media}</p>
            </div>
            
            <div class="bg-slate-800/80 p-6 rounded-2xl border border-slate-700 shadow-inner flex flex-col items-center justify-center min-h-[160px] mb-6 relative overflow-hidden group">
                
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 to-teal-500/10 opacity-50 group-hover:opacity-100 transition duration-500"></div>

                <div class="flex flex-col items-center relative z-10 animate-pulse-slow">
                    <span class="text-indigo-400 w-16 h-16 flex items-center justify-center mb-2">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </span>
                    <span class="text-slate-300 font-bold tracking-wide text-sm text-center">Menunggu Data Update...</span>
                </div>
            </div>
            
            <div class="text-xs text-slate-400 bg-slate-800 rounded-lg p-3 border border-slate-700 mb-6 flex gap-2">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Catatan: Menyimpan perubahan akan memperbarui data hafalan. Klasifikasi final menggunakan rekam SVM mesin di server.</span>
            </div>

            <button type="submit" form="formHafalanEdit" class="w-full bg-blue-500 hover:bg-blue-400 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui Data Hafalan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function hafalanEditForm() {
        return {}
    }
</script>
<style>
    .animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>
@endpush

@endsection
