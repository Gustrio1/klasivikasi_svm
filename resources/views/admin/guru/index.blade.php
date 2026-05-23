@extends('layouts.app')

@section('title', 'Manajemen Guru (Admin)')

@section('content')

<x-page-header
    title="Data Seluruh Guru"
    subtitle="Kelola akun guru pembimbing, NIP, dan spesialisasi mata pelajaran."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Guru', 'url' => null],
    ]"
>
    <a href="{{ route('admin.guru.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Guru Baru
    </a>
</x-page-header>

<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Nama Guru</th>
                    <th class="table-th text-center">NIP</th>
                    <th class="table-th text-left">Mata Pelajaran</th>
                    <th class="table-th text-center">Kontak</th>
                    <th class="table-th text-center">Status</th>
                    <th class="table-th text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($gurus as $guru)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">
                            {{ $gurus->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $guru->user->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $guru->user->email }}</div>
                        </td>
                        <td class="table-td text-center font-mono text-sm text-gray-600">
                            {{ $guru->nip ?? '-' }}
                        </td>
                        <td class="table-td">
                            <div class="text-sm text-gray-700">{{ $guru->mata_pelajaran ?? '-' }}</div>
                        </td>
                        <td class="table-td text-center text-sm text-gray-600">
                            {{ $guru->no_telp ?? '-' }}
                        </td>
                        <td class="table-td text-center">
                            @if($guru->is_active)
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Non-aktif</span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.guru.edit', $guru->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus guru {{ $guru->user->nama_lengkap }} secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gurus->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $gurus->links() }}
        </div>
    @endif
</div>

@endsection
