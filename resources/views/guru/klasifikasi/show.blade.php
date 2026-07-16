@extends('layouts.app')

@section('title', 'Detail Klasifikasi (Guru)')

@section('content')

<x-page-header
    title="Detail Hasil Klasifikasi SVM"
    subtitle="Analisis mendalam hasil prediksi model untuk setoran hafalan siswa."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hasil Klasifikasi', 'url' => route('guru.hasil-klasifikasi.index')],
        ['label' => 'Detail', 'url' => null],
    ]"
/>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Informasi Utama & Hasil --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Card Hasil Prediksi --}}
        <div class="card p-0 overflow-hidden border-none shadow-xl">
            <div class="p-6 bg-gradient-to-br {{ $hasil->kelas_prediksi == 'Lulus' ? 'from-emerald-500 to-emerald-700' : 'from-red-500 to-red-700' }} text-white">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest">Prediksi Model SVM</span>
                    <span class="text-xs opacity-80">{{ \Carbon\Carbon::parse($hasil->tanggal_klasifikasi)->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <div class="w-auto px-6 h-24 bg-white/20 rounded-3xl flex items-center justify-center text-3xl font-black shadow-inner">
                        {{ strtoupper($hasil->kelas_prediksi) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-black">Status {{ $hasil->kelas_prediksi }}</h3>
                        <p class="text-white/80 mt-1">
                            @if($hasil->kelas_prediksi == 'Lulus')
                                Sangat Baik - Siswa menunjukkan penguasaan hafalan yang memenuhi standar.
                            @else
                                Perlu Bimbingan Khusus - Diperlukan pengulangan intensif untuk mencapai target.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white grid grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Confidence Score</p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500" style="width: {{ $hasil->confidence_score * 100 }}%"></div>
                        </div>
                        <span class="text-lg font-black text-gray-800">{{ number_format($hasil->confidence_score * 100, 1) }}%</span>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Model Digunakan</p>
                        <p class="text-sm font-bold text-gray-800">{{ $hasil->modelSvm->versi_model }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Detail Evaluasi Semester --}}
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Detail Evaluasi Semester
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-teal-50 rounded-xl text-center">
                    <p class="text-xs text-teal-600 font-bold uppercase mb-1">Periode Semester</p>
                    <p class="text-xl font-black text-teal-800">{{ $hasil->periode_semester }}</p>
                </div>
                <div class="p-4 bg-teal-50 rounded-xl text-center">
                    <p class="text-xs text-teal-600 font-bold uppercase mb-1">Total Surat</p>
                    <p class="text-xl font-black text-teal-800">{{ $hasil->total_surah }} Surat</p>
                </div>
                <div class="p-4 bg-indigo-50 rounded-xl text-center">
                    <p class="text-xs text-indigo-600 font-bold uppercase mb-1">Modus Media Belajar</p>
                    <p class="text-lg font-black text-indigo-800">
                        @php
                            $vector = is_array($hasil->vector_svm) ? $hasil->vector_svm : json_decode($hasil->vector_svm, true);
                            $id_media = $vector['id_media'] ?? '-';
                            $media = \App\Models\MediaHafalan::find($id_media);
                        @endphp
                        {{ $media ? $media->nama_media : $id_media }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Fitur SVM (Input Vector) --}}
        <div class="card p-6 bg-slate-50 border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                Vector Fitur SVM
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $vector = is_array($hasil->vector_svm) ? $hasil->vector_svm : json_decode($hasil->vector_svm, true);
                @endphp
                @php
                    $usiaSiswa = $hasil->siswa?->tanggal_lahir 
                        ? date('Y') - $hasil->siswa->tanggal_lahir
                        : ($vector['usia'] ?? '-');
                @endphp
                <div class="p-3 bg-white rounded-lg border border-slate-200 shadow-sm">
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">X₁ (Usia)</p>
                    <p class="text-lg font-black text-slate-700">{{ $usiaSiswa }} Thn</p>
                </div>
                <div class="p-3 bg-white rounded-lg border border-slate-200 shadow-sm">
                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">X₂ (ID Media)</p>
                    <p class="text-lg font-black text-slate-700">{{ $vector['id_media'] ?? '-' }}</p>
                </div>
                {{-- Fitur tambahan jika ada di vector --}}
                @foreach(collect($vector)->except(['usia', 'id_media', 'id_siswa', 'siswa', 'surah', 'jumlah_ayat']) as $key => $val)
                    <div class="p-3 bg-white rounded-lg border border-slate-200 shadow-sm">
                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">{{ strtoupper($key) }}</p>
                        <p class="text-lg font-black text-slate-700">{{ is_array($val) ? json_encode($val) : $val }}</p>
                    </div>
                @endforeach
            </div>
            <p class="text-[10px] text-slate-400 mt-4 italic font-medium">Data di atas adalah parameter numerik yang diproses untuk menentukan kelas kelulusan.</p>
        </div>

    </div>

    {{-- Kanan: Identitas Siswa & Guru --}}
    <div class="space-y-6">

        {{-- Card Siswa --}}
        <div class="card p-6">
            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Profil Siswa</h4>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800">{{ $hasil->siswa->user->nama_lengkap ?? '-' }}</h5>
                    <p class="text-sm text-gray-500 font-mono">NISN: {{ $hasil->siswa->nisn ?? '-' }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-400">Kelas</span>
                    <span class="text-sm font-bold text-gray-700">Tahfidz - {{ $hasil->siswa->kelas ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-400">Usia Saat Ini</span>
                    <span class="text-sm font-bold text-gray-700">
                        {{ $hasil->siswa?->tanggal_lahir ? (date('Y') - $hasil->siswa->tanggal_lahir) . ' Tahun' : '-' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('guru.siswa.show', $hasil->id_siswa) }}" class="btn-block mt-6 flex items-center justify-center gap-2 p-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm font-bold text-gray-600 transition">
                Lihat Profil Lengkap
            </a>
        </div>

        {{-- Card Guru --}}
        <div class="card p-6 bg-indigo-50/30 border-indigo-100">
            <h4 class="text-sm font-bold text-indigo-400 uppercase tracking-widest mb-4">Guru Pembimbing</h4>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-gray-800">{{ $hasil->siswa->guru->user->nama_lengkap ?? '-' }}</h5>
                    <p class="text-[10px] text-gray-500 font-mono tracking-tighter">NIP: {{ $hasil->siswa->guru->nip ?? '-' }}</p>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
