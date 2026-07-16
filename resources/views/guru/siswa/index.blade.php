@extends('layouts.app')

@section('title', 'Daftar Siswa Bimbingan')

@section('content')

<x-page-header
    title="Daftar Siswa Bimbingan"
    subtitle="Kelola data siswa yang berada di bawah bimbingan Anda"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Siswa', 'url' => null],
    ]"
/>

{{-- ── Search & Filter Bar ───────────────────────────────────── --}}
<div class="card mb-5 p-4">
    <form method="GET" action="{{ route('guru.siswa.index') }}" id="form-search-guru-siswa">
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

            {{-- Filter Kelas (dinamis dari DB) --}}
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
                    <a href="{{ route('guru.siswa.index') }}" class="btn-secondary flex items-center gap-1 px-4">
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
            <div class="mt-3 flex items-center gap-2 text-sm text-teal-700 bg-teal-50 rounded-lg px-3 py-2">
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

<div class="mb-4 flex justify-end">
    <a href="{{ route('guru.siswa.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Siswa
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="table-th text-center w-12">No</th>
                    <th class="table-th">Profil Siswa</th>
                    <th class="table-th">NISN</th>
                    <th class="table-th text-center">Kelas</th>
                    <th class="table-th text-center">Kelas Klasifikasi</th>
                    <th class="table-th text-center">Total Hafalan</th>
                    <th class="table-th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($siswas as $index => $siswa)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="table-td text-center text-gray-400 text-sm">{{ $siswas->firstItem() + $index }}</td>
                        <td class="table-td">
                            <div class="flex items-center gap-3">
                                @php
                                    $initials = collect(explode(' ', $siswa->user->nama_lengkap ?? 'S'))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
                                @endphp
                                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $siswa->user->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="table-td text-gray-600 font-mono text-sm">{{ $siswa->nisn ?? '-' }}</td>
                        <td class="table-td text-center">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">{{ $siswa->kelas ?? '-' }}</span>
                        </td>
                        <td class="table-td text-center">
                            @php
                                $lastKlasifikasi = strtoupper($siswa->hasilKlasifikasis->first()->kelas_prediksi ?? '');
                            @endphp
                            @if($lastKlasifikasi)
                                @php
                                    $lulus = in_array($lastKlasifikasi, ['A', 'B']);
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold shadow-sm {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <span class="text-gray-700 font-bold bg-gray-100 w-8 h-8 inline-flex items-center justify-center rounded-full">{{ $siswa->dataHafalans->count() ?? 0 }}</span>
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-2 text-sm">
                                <a href="{{ route('guru.siswa.show', $siswa->id) }}" class="btn-secondary py-1.5 px-3">Detail</a>
                                <a href="{{ route('guru.hafalan.create', ['id_siswa' => $siswa->id]) }}" class="btn-primary py-1.5 px-3">Input Hafalan</a>
                                <form action="{{ route('guru.siswa.destroy', $siswa->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Hapus siswa {{ $siswa->user->nama_lengkap }} beserta seluruh data hafalannya secara permanen? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 py-1.5 px-3 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition text-xs font-semibold"
                                        title="Hapus Siswa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-16 text-center">
                                @if(request('search') || request('kelas') || request('jenis_kelamin'))
                                    <p class="text-5xl mb-3">🔍</p>
                                    <p class="text-gray-500 font-medium font-semibold text-lg">Tidak ada siswa yang cocok</p>
                                    <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci atau filter pencarian.</p>
                                    <a href="{{ route('guru.siswa.index') }}" class="btn-secondary mt-4 inline-block">Reset Pencarian</a>
                                @else
                                    <p class="text-5xl mb-3">👥</p>
                                    <p class="text-gray-500 font-medium font-semibold text-lg">Belum ada data siswa</p>
                                    <p class="text-gray-400 text-sm mt-1">Silakan tambah data siswa pertama bimbingan Anda.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($siswas->hasPages())
    <div class="mt-4">
        {{ $siswas->withQueryString()->links() }}
    </div>
@endif

@endsection
