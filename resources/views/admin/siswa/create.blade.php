@extends('layouts.app')

@section('title', 'Tambah Siswa Baru (Admin)')

@section('content')

    <x-page-header title="Registrasi Siswa Tahfidz"
        subtitle="Daftarkan siswa baru ke dalam sistem dan tentukan pembimbing yang tepat." :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Manajemen Siswa', 'url' => route('admin.siswa.index')],
            ['label' => 'Tambah Siswa', 'url' => null],
        ]" />

    <div class="max-w-4xl mx-auto pb-12">
        <div class="card overflow-hidden border-none shadow-2xl">
            {{-- Header Form --}}
            <div class="bg-gradient-to-r from-teal-500 to-emerald-600 px-8 py-10 text-white relative">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black">Formulir Pendaftaran Siswa</h2>
                    <p class="text-emerald-50/70 text-sm mt-1">Lengkapi data profil dan tentukan guru pembimbing yang
                        bertanggung jawab.</p>
                </div>
                {{-- Dekorasi --}}
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z" />
                    </svg>
                </div>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-8 bg-white">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan Input</h3>
                                <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-10">

                    {{-- Kolom Kiri: Akun & Personal --}}
                    <div class="space-y-8">
                        <div>
                            <h3
                                class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                                <span
                                    class="w-6 h-6 bg-indigo-100 text-indigo-600 rounded flex items-center justify-center text-[10px]">01</span>
                                Akun & Identitas
                            </h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label-base text-gray-700">Username <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="username" class="input-base @error('username') border-red-500 ring-red-500 @enderror" placeholder="siswa_baru"
                                            value="{{ old('username') }}" required>
                                        @error('username')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="label-base text-gray-700">NISN</label>
                                        <input type="text" name="nisn" class="input-base @error('nisn') border-red-500 ring-red-500 @enderror" placeholder="0012xxxxxx"
                                            value="{{ old('nisn') }}">
                                        @error('nisn')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="label-base text-gray-700">Nama Lengkap Siswa <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="nama_lengkap" class="input-base @error('nama_lengkap') border-red-500 ring-red-500 @enderror"
                                        placeholder="Nama Sesuai Akta Kelahiran" value="{{ old('nama_lengkap') }}" required>
                                    @error('nama_lengkap')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- <div>
                                            <label class="label-base text-gray-700">Email Wali / Siswa <span class="text-red-500">*</span></label>
                                            <input type="email" name="email" class="input-base" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                                        </div> -->
                            </div>
                        </div>

                        <div>
                            <h3
                                class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2 italic">
                                Keamanan
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label-base text-gray-700">Password <span
                                            class="text-red-500">*</span></label>
                                    <input type="password" name="password" class="input-base @error('password') border-red-500 ring-red-500 @enderror" placeholder="••••••••"
                                        required>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="label-base text-gray-700">Konfirmasi <span
                                            class="text-red-500">*</span></label>
                                    <input type="password" name="password_confirmation" class="input-base"
                                        placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Detail Siswa & Penugasan --}}
                    <div class="space-y-8">
                        <div>
                            <h3
                                class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                                <span
                                    class="w-6 h-6 bg-teal-100 text-teal-600 rounded flex items-center justify-center text-[10px]">02</span>
                                Profil & Penugasan
                            </h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label-base text-gray-700">Kelas Tahfidz</label>
                                        <input type="text" class="input-base @error('kelas') border-red-500 ring-red-500 @enderror" name="kelas" value="{{ old('kelas') }}"
                                            required>
                                        @error('kelas')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="label-base text-gray-700">Jenis Kelamin</label>
                                        <div class="flex gap-4 mt-2">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                                <span class="text-sm font-medium">L</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                                                <span class="text-sm font-medium">P</span>
                                            </label>
                                        </div>
                                        @error('jenis_kelamin')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="label-base text-gray-700">Tahun Lahir <span
                                            class="text-teal-600">(Penting untuk SVM Usia)</span></label>
                                    <input type="number" name="tanggal_lahir" class="input-base @error('tanggal_lahir') border-red-500 ring-red-500 @enderror"
                                        placeholder="Contoh: 2005" min="1900" max="{{ date('Y') }}"
                                        value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-[10px] text-gray-400 mt-1 italic">Data ini digunakan untuk perhitungan
                                        klasifikasi otomatis berdasarkan rentang usia.</p>
                                </div>

                                <div class="pt-4 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                                    <label class="label-base text-indigo-700 flex items-center gap-2 mb-2 font-black">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Guru Pembimbing <span class="text-red-500">*</span>
                                    </label>
                                    <select name="id_guru" class="input-base bg-white border-indigo-200 @error('id_guru') border-red-500 ring-red-500 @enderror" required>
                                        <option value="" disabled selected>Pilih Pembimbing...</option>
                                        @foreach($gurus as $guru)
                                            <option value="{{ $guru->id }}" {{ old('id_guru') == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->user->nama_lengkap }} ({{ $guru->nip ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_guru')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer Form --}}
                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-[10px] text-gray-400 max-w-xs font-medium">
                        <p>Siswa yang didaftarkan akan muncul secara otomatis di dashboard Guru Pembimbing yang terpilih.
                        </p>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="{{ route('admin.siswa.index') }}"
                            class="flex-1 md:flex-none px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl transition text-center">Batal</a>
                        <button type="submit"
                            class="flex-1 md:flex-none px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-xl shadow-emerald-100 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Data Siswa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection