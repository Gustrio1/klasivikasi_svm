@extends('layouts.app')

@section('title', 'Manajemen User (Admin)')

@section('content')

<x-page-header
    title="Data Pengguna Sistem"
    subtitle="Kelola seluruh akun Admin, Guru, dan Siswa dalam satu tempat."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen User', 'url' => null],
    ]"
>
    <a href="{{ route('admin.users.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah User Baru
    </a>
</x-page-header>

<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Nama Lengkap</th>
                    <th class="table-th text-left">Username</th>
                    <th class="table-th text-center">Role</th>
                    <th class="table-th text-center">Status</th>
                    <th class="table-th text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">
                            {{ $users->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $u->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                        </td>
                        <td class="table-td font-mono text-sm text-gray-600">
                            {{ $u->username }}
                        </td>
                        <td class="table-td text-center">
                            @if($u->role === 'admin')
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded uppercase tracking-widest">Admin</span>
                            @elseif($u->role === 'guru')
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded uppercase tracking-widest">Guru</span>
                            @else
                                <span class="px-2 py-1 bg-teal-100 text-teal-700 text-[10px] font-bold rounded uppercase tracking-widest">Siswa</span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            @if($u->is_active)
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Non-aktif</span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">Belum ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection
