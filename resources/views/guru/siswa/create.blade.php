@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<x-page-header
    title="Tambah Siswa Baru"
    subtitle="Masukkan data diri dan detail siswa yang akan dibimbing"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Siswa', 'url' => route('guru.siswa.index')],
        ['label' => 'Tambah', 'url' => null],
    ]"
/>

<div class="card p-6 md:p-8">
    <form method="POST" action="{{ route('guru.siswa.store') }}">
        @csrf
        
        {{-- Input hidden id_guru --}}
        <input type="hidden" name="id_guru" value="{{ auth()->user()->guru->id ?? '' }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Kolom Kiri: Akun Siswa --}}
            <div class="space-y-5">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Akun</h3>
                
                <div>
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input @error('nama_lengkap') border-red-500 @enderror" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Budi">
                    @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="username" class="form-label">Username <span class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" class="form-input @error('username') border-red-500 @enderror" value="{{ old('username') }}" required placeholder="Contoh: ahmadbudi">
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="password" class="form-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" class="form-input @error('password') border-red-500 @enderror" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                </div>
            </div>

            {{-- Kolom Kanan: Data Akademik & Personal --}}
            <div class="space-y-5">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Data Akademik</h3>
                
                <div>
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" id="nisn" name="nisn" class="form-input @error('nisn') border-red-500 @enderror" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional">
                    @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="kelas" class="form-label">Kelas Akademik</label>
                    <select id="kelas" name="kelas" class="form-input @error('kelas') border-red-500 @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @php
                            $kelasList = ['VII-A', 'VII-B', 'VII-C', 'VIII-A', 'VIII-B', 'VIII-C', 'IX-A', 'IX-B', 'IX-C'];
                        @endphp
                        @foreach($kelasList as $kls)
                            <option value="{{ $kls }}" {{ old('kelas') == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                        @endforeach
                    </select>
                    @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-6 mt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="L" class="text-teal-600 focus:ring-teal-500" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                            <span class="text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="P" class="text-teal-600 focus:ring-teal-500" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                            <span class="text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="tanggal_lahir" class="form-label">Tahun Lahir</label>
                    <input type="number" id="tanggal_lahir" name="tanggal_lahir" class="form-input @error('tanggal_lahir') border-red-500 @enderror"
                        placeholder="Contoh: 2005" min="1900" max="{{ date('Y') }}" value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('guru.siswa.index') }}" class="btn-secondary px-6">Batal</a>
            <button type="submit" class="btn-primary px-8">Simpan Data Siswa</button>
        </div>
    </form>
</div>

@endsection
