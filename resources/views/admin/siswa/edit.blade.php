@extends('layouts.app')

@section('title', 'Edit Data Siswa (Admin)')

@section('content')

<x-page-header
    title="Pembaruan Profil Siswa"
    subtitle="Update data akademik dan informasi pendamping Siswa Tahfidz."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Siswa', 'url' => route('admin.siswa.index')],
        ['label' => 'Edit Siswa', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card overflow-hidden border-none shadow-2xl">
        {{-- Header Form --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-10 text-white relative">
            <div class="relative z-10 font-bold">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-2xl font-black">Profil Siswa: {{ $siswa->user->nama_lengkap }}</h2>
                <p class="text-amber-50/70 text-sm mt-1 font-normal italic">Terakhir diperbarui: {{ $siswa->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="p-8 bg-white">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">
                
                {{-- Kiri: Akun & Identitas --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2 flex items-center gap-2">
                        Akun & Keamanan
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label-base text-gray-700">Username</label>
                                <input type="text" class="input-base bg-gray-50 text-gray-400" value="{{ $siswa->user->username }}" disabled>
                            </div>
                            <div>
                                <label class="label-base text-gray-700">NISN</label>
                                <input type="text" name="nisn" class="input-base" value="{{ old('nisn', $siswa->nisn) }}">
                            </div>
                        </div>

                        <div>
                            <label class="label-base text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="input-base" value="{{ old('nama_lengkap', $siswa->user->nama_lengkap) }}" required>
                        </div>

                        <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                            <p class="text-[10px] text-orange-600 font-bold mb-2 italic">Ganti Password (Opsional)</p>
                            <input type="password" name="password" class="input-base" placeholder="Password Baru">
                        </div>
                    </div>
                </div>

                {{-- Kanan: Personal & Guru --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2 flex items-center gap-2">
                        Akademik & Personal
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label-base text-gray-700">Kelas</label>
                                <input type="text" name="kelas" class="input-base" value="{{ old('kelas', $siswa->kelas) }}">
                            </div>
                            <div>
                                <label class="label-base text-gray-700">Gender</label>
                                <div class="flex gap-4 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                                        <span class="text-sm font-medium">L</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'checked' : '' }} required>
                                        <span class="text-sm font-medium">P</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="label-base text-gray-700">Tahun Lahir <span class="text-teal-600 font-bold">(Penting)</span></label>
                            <input type="number" name="tanggal_lahir" class="input-base" placeholder="Contoh: 2005"
                                min="1900" max="{{ date('Y') }}"
                                value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                        </div>

                        <div class="pt-4 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                            <label class="label-base text-indigo-700 font-black mb-2 block">Guru Pembimbing Terpilih</label>
                            <select name="id_guru" class="input-base bg-white" required>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('id_guru', $siswa->id_guru) == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->user->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.siswa.index') }}" class="px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-xl shadow-amber-100 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
