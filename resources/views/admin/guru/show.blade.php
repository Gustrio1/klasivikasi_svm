@extends('layouts.app')

@section('title', 'Detail Guru (Admin)')

@section('content')

<x-page-header
    title="Profil Lengkap Guru"
    subtitle="Informasi kepegawaian, kontak, dan daftar siswa bimbingan."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Guru', 'url' => route('admin.guru.index')],
        ['label' => $guru->user->nama_lengkap, 'url' => null],
    ]"
>
    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Guru
    </a>
</x-page-header>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Kiri: Bio Guru --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6 text-center shadow-lg border-t-4 border-t-indigo-500">
            <div class="w-24 h-24 mx-auto bg-indigo-50 rounded-3xl flex items-center justify-center text-indigo-600 mb-4 shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800">{{ $guru->user->nama_lengkap }}</h3>
            <p class="text-sm font-mono text-gray-400 mt-1">NIP: {{ $guru->nip ?? '-' }}</p>
            
            <div class="mt-8 pt-8 border-t border-gray-100 space-y-4 text-left">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Email</p>
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $guru->user->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Mata Pelajaran</p>
                    <p class="text-sm font-medium text-gray-700">{{ $guru->mata_pelajaran ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kontak</p>
                    <p class="text-sm font-medium text-gray-700">{{ $guru->no_telp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kanan: Statistik & Daftar Siswa --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- Statistik --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card p-6 border-l-4 border-l-indigo-500">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total Siswa</p>
                <h4 class="text-3xl font-black text-gray-800">{{ $guru->siswas->count() }}</h4>
            </div>
            <div class="card p-6 border-l-4 border-l-emerald-500">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total Hafalan</p>
                <h4 class="text-3xl font-black text-gray-800">{{ $guru->dataHafalans->count() }}</h4>
            </div>
        </div>

        {{-- Tabel Siswa Bimbingan --}}
        <div class="card p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 italic text-gray-400 text-sm">
                Siswa yang dibimbing oleh {{ $guru->user->nama_lengkap }}
            </div>
            <div class="overflow-x-auto">
                <table class="table-base w-full">
                    <thead class="bg-gray-50 uppercase text-[10px] tracking-widest">
                        <tr>
                            <th class="table-th text-left">Nama Siswa</th>
                            <th class="table-th text-center">NISN</th>
                            <th class="table-th text-center">Kelas</th>
                            <th class="table-th text-center">Status</th>
                            <th class="table-th text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($guru->siswas as $siswa)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="table-td">
                                    <div class="font-bold text-gray-800">{{ $siswa->user->nama_lengkap }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $siswa->user->email }}</div>
                                </td>
                                <td class="table-td text-center font-mono text-sm">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="table-td text-center font-bold text-teal-600">{{ $siswa->kelas ?? '-' }}</td>
                                <td class="table-td text-center">
                                    @if($siswa->user->is_active)
                                        <span class="badge-success">Aktif</span>
                                    @else
                                        <span class="badge-danger">Off</span>
                                    @endif
                                </td>
                                <td class="table-td text-center">
                                    <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="text-indigo-600 hover:text-indigo-800">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada siswa bimbingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection
