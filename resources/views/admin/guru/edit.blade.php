@extends('layouts.app')

@section('title', 'Edit Data Guru (Admin)')

@section('content')

<x-page-header
    title="Pembaruan Profil Guru"
    subtitle="Update informasi kepegawaian dan akun guru pembimbing."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Guru', 'url' => route('admin.guru.index')],
        ['label' => 'Edit Guru', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card overflow-hidden border-none shadow-2xl">
        {{-- Header Form --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-10 text-white relative">
            <div class="relative z-10 font-black">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-2xl">Edit Informasi: {{ $guru->user->nama_lengkap }}</h2>
                <p class="text-amber-50/70 text-sm mt-1 font-normal italic">Terakhir diperbarui: {{ $guru->updated_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
        </div>

        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" class="p-8 bg-white">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">
                
                {{-- Akun --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2 border-b pb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Pengaturan Akun
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="label-base text-gray-700">Username</label>
                            <input type="text" class="input-base bg-gray-50 border-gray-200 text-gray-400 cursor-not-allowed" value="{{ $guru->user->username }}" disabled>
                            <p class="text-[10px] text-gray-400 mt-1 italic">Username bersifat permanen.</p>
                        </div>
                        <div>
                            <label class="label-base text-gray-700">Email Akun</label>
                            <input type="email" name="email" class="input-base" value="{{ old('email', $guru->user->email) }}" required>
                        </div>
                        <div class="pt-4 p-4 bg-orange-50 rounded-xl border border-orange-100 italic">
                            <label class="text-xs font-bold text-orange-700 block mb-1">Keamanan</label>
                            <p class="text-[10px] text-orange-400 mb-2">Kosongkan jika tidak ingin mengubah password.</p>
                            <input type="password" name="password" class="input-base bg-white" placeholder="Password Baru">
                        </div>
                    </div>
                </div>

                {{-- Profil --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2 border-b pb-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Identitas Kepegawaian
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="label-base text-gray-700">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama_lengkap" class="input-base" value="{{ old('nama_lengkap', $guru->user->nama_lengkap) }}" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label-base text-gray-700">NIP</label>
                                <input type="text" name="nip" class="input-base" value="{{ old('nip', $guru->nip) }}">
                            </div>
                            <div>
                                <label class="label-base text-gray-700">WhatsApp</label>
                                <input type="text" name="no_telp" class="input-base" value="{{ old('no_telp', $guru->no_telp) }}">
                            </div>
                        </div>
                        <div>
                            <label class="label-base text-gray-700">Spesialisasi</label>
                            <select name="mata_pelajaran" class="input-base">
                                @foreach(["Tahfidz Al-Qur'an", "Tahsin & Tajwid", "Fiqih & Akidah", "Bahasa Arab"] as $mp)
                                    <option value="{{ $mp }}" {{ old('mata_pelajaran', $guru->mata_pelajaran) == $mp ? 'selected' : '' }}>{{ $mp }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.guru.index') }}" class="px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-xl shadow-amber-100 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
