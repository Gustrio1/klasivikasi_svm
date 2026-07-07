@extends('layouts.app')

@section('title', 'Hafalan Saya')

@section('content')

<x-page-header
    title="Hafalan Saya"
    subtitle="Daftar semua sesi hafalan yang telah diinput"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hafalan Saya', 'url' => null],
    ]"
/>

{{-- ── Filter Bar ───────────────────────────────────────── --}}
<div class="card mb-5">
    <form method="GET" action="{{ route('siswa.hafalan.index') }}"
          class="flex flex-col sm:flex-row gap-3">
        {{-- Pencarian Surah --}}
        <div class="flex-1">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama surah..."
                   class="form-input">
        </div>
        {{-- Filter Kelas --}}
        <div class="sm:w-48">
            <select name="kelas" class="form-input">
                <option value="">Semua Kelas</option>
                <option value="A" {{ request('kelas') === 'A' ? 'selected' : '' }}>Kelas A</option>
                <option value="B" {{ request('kelas') === 'B' ? 'selected' : '' }}>Kelas B</option>
                <option value="C" {{ request('kelas') === 'C' ? 'selected' : '' }}>Kelas C</option>
            </select>
        </div>
        <button type="submit" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Filter
        </button>
        @if(request()->hasAny(['search','kelas']))
            <a href="{{ route('siswa.hafalan.index') }}" class="btn-secondary shrink-0">Reset</a>
        @endif
    </form>
</div>

{{-- ── Tabel Hafalan ────────────────────────────────────── --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Nama Surah</th>
                    <th class="table-th">Ayat</th>
                    <th class="table-th">Semester</th>
                    <th class="table-th">Tgl Input</th>
                    <th class="table-th">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($hafalan as $i => $hf)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-gray-400">{{ $hafalan->firstItem() + $i }}</td>
                        <td class="table-td font-semibold text-gray-800">{{ $hf->nama_surah }}</td>
                        <td class="table-td text-gray-500">{{ $hf->jumlah_ayat }} Ayat</td>
                        <td class="table-td text-gray-500 text-sm">{{ $hf->periode_semester ?? '-' }}</td>
                        <td class="table-td text-gray-500">
                            {{ \Carbon\Carbon::parse($hf->tanggal_input)->translatedFormat('d F Y') }}
                        </td>
                        <td class="table-td">
                            <a href="{{ route('siswa.hafalan.show', $hf->id) }}"
                               class="btn-secondary py-1.5 px-3 text-xs">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Belum ada data hafalan</p>
                                <p class="text-gray-400 text-sm mt-1">Hubungi guru untuk menginput hafalan Anda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($hafalan->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $hafalan->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
