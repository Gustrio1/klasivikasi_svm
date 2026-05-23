@extends('layouts.app')

@section('title', 'Tambah User Baru (Admin)')

@section('content')

    <x-page-header title="Registrasi Pengguna Sistem" subtitle="Buat akun baru secara manual untuk Admin, Guru, atau Siswa."
        :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Manajemen User', 'url' => route('admin.users.index')],
            ['label' => 'Tambah User', 'url' => null],
        ]" />

    <div class="max-w-4xl mx-auto pb-12">
        <div class="card overflow-hidden border-none shadow-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-10 text-white relative">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black">Input Data Pengguna Baru</h2>
                    <p class="text-blue-50/70 text-sm mt-1">Gunakan formulir ini untuk pendaftaran manual di luar sistem
                        otomatis.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 bg-white">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Dasar Akun --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Informasi Akun
                        </h3>

                        <div>
                            <label class="label-base">Username</label>
                            <input type="text" name="username"
                                class="input-base @error('username') border-red-500 @enderror" placeholder="user_unique_id"
                                value="{{ old('username') }}" required>
                            @error('username') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="label-base">Role Akses</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['admin', 'guru', 'siswa'] as $role)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="role" value="{{ $role }}" class="peer hidden" {{ old('role', 'siswa') == $role ? 'checked' : '' }}>
                                        <div
                                            class="px-3 py-2 text-center text-xs font-bold border border-gray-100 rounded-lg hover:bg-gray-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition uppercase tracking-tighter">
                                            {{ $role }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 pt-2">
                            <div>
                                <label class="label-base">Password</label>
                                <input type="password" name="password" class="input-base" placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="label-base">Konfirmasi</label>
                                <input type="password" name="password_confirmation" class="input-base"
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    {{-- Personal --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Data Personal
                        </h3>

                        <div>
                            <label class="label-base">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="input-base" placeholder="Nama Terang"
                                value="{{ old('nama_lengkap') }}" required>
                        </div>

                        <!-- <div>
                            <label class="label-base">Email</label>
                            <input type="email" name="email" class="input-base" placeholder="email@domain.com" value="{{ old('email') }}" required>
                        </div> -->

                        <div class="pt-6">
                            <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 italic">
                                <p class="text-[10px] text-blue-600 leading-relaxed font-medium">Catatan: Pendaftaran
                                    melalui form ini tidak akan membuat profil khusus (Siswa/Guru) secara otomatis. Profil
                                    tambahan harus dikonfigurasi melalui modul masing-masing.</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl transition">Batal</a>
                    <button type="submit"
                        class="px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-xl shadow-blue-100 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Buat User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection