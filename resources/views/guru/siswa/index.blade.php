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

<div class="mb-5 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="flex-1 w-full flex flex-col sm:flex-row gap-3">
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('guru.siswa.index') }}" class="flex w-full gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NISN..." class="form-input w-full sm:max-w-xs">
            <select name="kelas" class="form-input w-full sm:max-w-xs">
                <option value="">Semua Kelas</option>
                <option value="VII-A" {{ request('kelas') == 'VII-A' ? 'selected' : '' }}>VII-A</option>
                <option value="VII-B" {{ request('kelas') == 'VII-B' ? 'selected' : '' }}>VII-B</option>
                <option value="VIII-A" {{ request('kelas') == 'VIII-A' ? 'selected' : '' }}>VIII-A</option>
                <option value="IX-A" {{ request('kelas') == 'IX-A' ? 'selected' : '' }}>IX-A</option>
            </select>
            <button type="submit" class="btn-primary px-4">Cari</button>
            @if(request('search') || request('kelas'))
                <a href="{{ route('guru.siswa.index') }}" class="btn-secondary px-4">Reset</a>
            @endif
        </form>
    </div>
    <div>
        <a href="{{ route('guru.siswa.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Siswa
        </a>
    </div>
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-16 text-center">
                                <p class="text-5xl mb-3">👥</p>
                                <p class="text-gray-500 font-medium font-semibold text-lg">Belum ada data siswa</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambah data siswa pertama bimbingan Anda.</p>
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
