@extends('layouts.app')

@section('title', 'Edit User (Admin)')

@section('content')

<x-page-header
    title="Pembaruan Data Pengguna"
    subtitle="Edit kredensial akun dan hak akses sistem."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen User', 'url' => route('admin.users.index')],
        ['label' => 'Edit User', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card overflow-hidden border-none shadow-2xl">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-10 text-white relative">
            <div class="relative z-10 font-black">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-2xl">ID Pengguna: #{{ $user->id }}</h2>
                <p class="text-amber-50/70 text-sm mt-1 font-normal italic">Informasi Akun: {{ $user->username }}</p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 bg-white">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Kiri --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Kredensial</h3>
                    
                    <div>
                        <label class="label-base">Username</label>
                        <input type="text" class="input-base bg-gray-50 text-gray-400" value="{{ $user->username }}" disabled>
                        <p class="text-[10px] text-gray-400 mt-1 italic italic">Username dikunci oleh sistem.</p>
                    </div>

                    <div>
                        <label class="label-base">Role Akses</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['admin', 'guru', 'siswa'] as $role)
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="{{ $role }}" class="peer hidden" {{ old('role', $user->role) == $role ? 'checked' : '' }}>
                                    <div class="px-3 py-2 text-center text-xs font-bold border border-gray-100 rounded-lg hover:bg-gray-50 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500 transition uppercase tracking-tighter">
                                        {{ $role }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 bg-orange-50 rounded-xl border border-orange-100 mt-4">
                        <p class="text-[10px] text-orange-600 font-black uppercase mb-2">Reset Password</p>
                        <input type="password" name="password" class="input-base bg-white" placeholder="Isi hanya jika ingin ganti">
                        <input type="password" name="password_confirmation" class="input-base bg-white mt-2" placeholder="Ulangi password baru">
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">Bio Data</h3>
                    
                    <div>
                        <label class="label-base">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="input-base" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                    </div>

                    <div>
                        <label class="label-base">Email Aktif</label>
                        <input type="email" name="email" class="input-base" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="pt-6">
                        <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:border-orange-200 transition">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-5 h-5 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            <div>
                                <p class="text-sm font-bold text-gray-800">Status Akun Aktif</p>
                                <p class="text-[10px] text-gray-400 italic">User akan segera kehilangan akses jika dinonaktifkan.</p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-xl shadow-amber-100 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update Account
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
