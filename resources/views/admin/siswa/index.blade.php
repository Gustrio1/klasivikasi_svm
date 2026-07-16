@extends('layouts.app')

@section('title', 'Manajemen Siswa (Admin)')

@section('content')

<x-page-header
    title="Data Seluruh Siswa"
    subtitle="Kelola informasi akun, NISN, dan pendamping guru untuk seluruh siswa."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Siswa', 'url' => null],
    ]"
>
    <a href="{{ route('admin.siswa.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Siswa Baru
    </a>
</x-page-header>

{{-- ── Search & Filter Bar ───────────────────────────────────── --}}
<div class="card mb-5 p-4">
    <form method="GET" action="{{ route('admin.siswa.index') }}" id="form-search-siswa">
        <div class="flex flex-col sm:flex-row gap-3 items-end">

            {{-- Search nama / NISN --}}
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Cari Siswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="search"
                        id="search-input"
                        value="{{ request('search') }}"
                        placeholder="Nama lengkap atau NISN..."
                        autocomplete="off"
                        class="form-input pl-9 w-full"
                    >
                </div>
            </div>

            {{-- Filter Kelas --}}
            <div class="w-full sm:w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Kelas</label>
                <select name="kelas" class="form-input w-full">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Jenis Kelamin --}}
            <div class="w-full sm:w-44">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-input w-full">
                    <option value="">Semua</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2 flex-shrink-0">
                <button type="submit" class="btn-primary flex items-center gap-2 px-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    Cari
                </button>
                @if(request('search') || request('kelas') || request('jenis_kelamin'))
                    <a href="{{ route('admin.siswa.index') }}" class="btn-secondary flex items-center gap-1 px-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>

        {{-- Info hasil pencarian --}}
        @if(request('search') || request('kelas') || request('jenis_kelamin'))
            <div class="mt-3 flex items-center gap-2 text-sm text-indigo-700 bg-indigo-50 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                </svg>
                <span>
                    Menampilkan <strong>{{ $siswas->total() }}</strong> siswa
                    @if(request('search')) · Pencarian: <strong>"{{ request('search') }}"</strong> @endif
                    @if(request('kelas')) · Kelas: <strong>{{ request('kelas') }}</strong> @endif
                    @if(request('jenis_kelamin')) · {{ request('jenis_kelamin') === 'L' ? '♂ Laki-laki' : '♀ Perempuan' }} @endif
                </span>
            </div>
        @endif
    </form>
</div>

{{-- ── Tabel Data Siswa ──────────────────────────────────────── --}}
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Siswa</th>
                    <th class="table-th text-center">NISN</th>
                    <th class="table-th text-center">Kelas</th>
                    <th class="table-th text-left">Guru Pembimbing</th>
                    <th class="table-th text-center">Status</th>
                    <th class="table-th text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($siswas as $siswa)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">
                            {{ $siswas->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="flex items-center gap-3">
                                @php
                                    $initials = collect(explode(' ', $siswa->user->nama_lengkap ?? 'S'))
                                        ->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
                                @endphp
                                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        @if(request('search'))
                                            {!! str_ireplace(
                                                request('search'),
                                                '<mark class="bg-yellow-200 text-yellow-900 rounded px-0.5">'.e(request('search')).'</mark>',
                                                e($siswa->user->nama_lengkap)
                                            ) !!}
                                        @else
                                            {{ $siswa->user->nama_lengkap }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-td text-center font-mono text-sm text-gray-600">
                            @if(request('search') && $siswa->nisn)
                                {!! str_ireplace(
                                    request('search'),
                                    '<mark class="bg-yellow-200 text-yellow-900 rounded px-0.5">'.e(request('search')).'</mark>',
                                    e($siswa->nisn)
                                ) !!}
                            @else
                                {{ $siswa->nisn ?? '-' }}
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded">{{ $siswa->kelas ?? '-' }}</span>
                        </td>
                        <td class="table-td">
                            <div class="text-sm font-medium text-gray-700">{{ $siswa->guru->user->nama_lengkap ?? 'Belum Ditentukan' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase">{{ $siswa->guru->nip ?? '-' }}</div>
                        </td>
                        <td class="table-td text-center">
                            @if($siswa->user->is_active)
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Non-aktif</span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Hapus siswa {{ $siswa->user->nama_lengkap }} beserta seluruh data hafalannya secara permanen?');">
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
                        <td colspan="7" class="py-16 text-center">
                            @if(request('search') || request('kelas') || request('jenis_kelamin'))
                                <p class="text-4xl mb-3">🔍</p>
                                <p class="text-gray-500 font-semibold">Tidak ada siswa yang cocok</p>
                                <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci atau filter pencarian.</p>
                                <a href="{{ route('admin.siswa.index') }}" class="btn-secondary mt-4 inline-block">Reset Pencarian</a>
                            @else
                                <p class="text-4xl mb-3">👥</p>
                                <p class="text-gray-500 font-semibold">Belum ada data siswa</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($siswas->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $siswas->links() }}
        </div>
    @endif
</div>

@endsection
