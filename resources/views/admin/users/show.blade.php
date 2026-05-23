@extends('layouts.app')

@section('title', 'Detail User (Admin)')

@section('content')

<x-page-header
    title="Profil Pengguna"
    subtitle="Detail informasi akun dan keterhubungan dengan data spesifik (Guru/Siswa)."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen User', 'url' => route('admin.users.index')],
        ['label' => $user->nama_lengkap, 'url' => null],
    ]"
>
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary">Edit User</a>
</x-page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card p-6 lg:col-span-1">
        <div class="text-center mb-6">
            <div class="w-24 h-24 mx-auto bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 mb-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $user->nama_lengkap }}</h3>
            <span class="text-sm text-gray-400 capitalize">{{ $user->role }}</span>
        </div>
        <div class="space-y-4">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Username</p>
                <p class="text-sm font-medium">{{ $user->username }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Email</p>
                <p class="text-sm font-medium">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Status</p>
                @if($user->is_active)
                    <span class="badge-success">Aktif</span>
                @else
                    <span class="badge-danger">Non-aktif</span>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
