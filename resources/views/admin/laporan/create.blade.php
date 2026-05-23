@extends('layouts.app')

@section('title', 'Cetak Laporan Baru')

@php
    $rolePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'guru.';
@endphp

@section('content')

<x-page-header
    title="Buat Laporan Pencapaian"
    subtitle="Susun dokumen perkembangan hafalan siswa dalam format PDF yang siap diprint"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan', 'url' => route($rolePrefix . 'laporan.index')],
        ['label' => 'Buat Laporan', 'url' => null],
    ]"
/>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <div class="lg:col-span-8">
        <div class="card p-6 md:p-8">
            <form action="{{ route($rolePrefix . 'laporan.store') }}" method="POST" id="formLaporan">
                @csrf
                
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-5">Parameter Pencetakan Laporan</h3>
                
                {{-- Pilih Siswa --}}
                <div class="mb-5">
                    <label for="id_siswa" class="form-label">Subjek Laporan (Siswa) <span class="text-red-500">*</span></label>
                    <select name="id_siswa" id="id_siswa" class="form-input bg-gray-50 @error('id_siswa') border-red-500 @enderror" required>
                        <option value="">-- Pilih Rekam Siswa --</option>
                        @foreach($siswas as $s)
                            {{-- Jika Guru, hanya siswa ampuannya. Jika admin, semua siswa. --}}
                            @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'guru' && $s->id_guru == auth()->user()->guru->id))
                                <option value="{{ $s->id }}" {{ old('id_siswa') == $s->id ? 'selected' : '' }}>
                                    {{ $s->user->nama_lengkap }} (Kelas: {{ $s->kelas ?? '-' }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('id_siswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                {{-- Judul Laporan --}}
                <div class="mb-5">
                    <label for="judul_laporan" class="form-label">Judul Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_laporan" id="judul_laporan" class="form-input @error('judul_laporan') border-red-500 @enderror" value="{{ old('judul_laporan', 'Laporan Evaluasi Hafalan Al-Qur\'an Siswa') }}" required placeholder="Contoh: Laporan Evaluasi Hafalan Tahfidz">
                    @error('judul_laporan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Periode --}}
                <div class="mb-5">
                    <label for="periode" class="form-label">Periode Evaluasi <span class="text-red-500">*</span></label>
                    <input type="text" name="periode" id="periode" class="form-input @error('periode') border-red-500 @enderror" value="{{ old('periode') }}" required placeholder="Contoh: Genap - Tahun Ajaran 2025/2026">
                    <p class="text-[11px] mt-1 text-gray-500">Periode ini akan dicantumkan di bagian kepala (*header*) laporan PDF.</p>
                    @error('periode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Pilih Guru Pembimbing (Khusus Admin) --}}
                @if(auth()->user()->role === 'admin')
                <div class="mb-6 p-4 bg-indigo-50/50 border border-indigo-100 rounded-xl">
                    <label for="id_guru" class="form-label text-indigo-900">Penanggung Jawab (Guru) <span class="text-red-500">*</span></label>
                    <select name="id_guru" id="id_guru" class="form-input @error('id_guru') border-red-500 @enderror" required>
                        <option value="">-- Pilih Guru Pembimbing --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id }}" {{ old('id_guru') == $g->id ? 'selected' : '' }}>
                                {{ $g->user->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] mt-1 text-indigo-600">Sebagai Admin, tentukan guru mana yang akan menempati *field* tanda tangan PDF.</p>
                    @error('id_guru') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                
                <div class="mt-8 flex gap-3">
                    <a href="{{ route($rolePrefix . 'laporan.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary flex items-center justify-center gap-2" onclick="this.innerHTML='<svg class=\'animate-spin h-5 w-5 text-white\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg> Memproses Ekspor PDF...';this.classList.add('opacity-75', 'cursor-not-allowed')">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Ekspor & Simpan ke PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="lg:col-span-4">
        <div class="card p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 border-none shadow-sm">
            <h3 class="font-bold border-b border-indigo-200 pb-2 mb-4 flex items-center gap-2 text-indigo-900">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Informasi Modul
            </h3>
            <ul class="text-sm text-indigo-800 space-y-4">
                <li class="flex gap-3">
                    <span class="mt-0.5 text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    <span>Modul ini akan mengekstrak seluruh historis hafalan siswa terpilih dari <strong>awal pencatatan</strong> dan mensintesisnya ke PDF.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-0.5 text-indigo-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    <span>Proses *render* dokumen utuh ke antarmuka <strong>domPDF</strong> mungkin membutuhkan waktu 2-10 detik jika rekam data siswa sudah terlalu gemuk.</span>
                </li>
            </ul>
        </div>
    </div>

</div>

@endsection
