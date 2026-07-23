@extends('layouts.app')

@section('title', 'Data Hafalan Siswa')

@section('content')

<x-page-header
    title="Data Hafalan Siswa"
    subtitle="Pantau dan kelola riwayat setoran hafalan siswa bimbingan Anda"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hafalan', 'url' => null],
    ]"
/>

<div class="mb-5 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div class="flex-1 w-full">
        <form method="GET" action="{{ route('guru.hafalan.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="form-input w-full md:w-auto md:min-w-[200px]">
            
            <input type="text" name="surah" value="{{ request('surah') }}" placeholder="Nama Surah" class="form-input w-full md:w-32">
            
            <select name="kelas" class="form-input w-full md:w-36">
                <option value="">Semua Kelas</option>
                <option value="A" {{ request('kelas') == 'A' ? 'selected' : '' }}>Kelas A (Baik)</option>
                <option value="B" {{ request('kelas') == 'B' ? 'selected' : '' }}>Kelas B (Cukup)</option>
                <option value="C" {{ request('kelas') == 'C' ? 'selected' : '' }}>Kelas C (Kurang)</option>
            </select>
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input w-full md:w-36" title="Tanggal Awal">
                <span class="text-gray-400">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input w-full md:w-36" title="Tanggal Akhir">
            </div>

            <button type="submit" class="btn-primary py-2 px-5">Filter</button>
            
            @if(request('search') || request('surah') || request('kelas') || request('start_date') || request('end_date'))
                <a href="{{ route('guru.hafalan.index') }}" class="btn-secondary py-2 px-4 hover:bg-gray-100">Reset</a>
            @endif
        </form>
    </div>
    
    <div>
        <a href="{{ route('guru.hafalan.create') }}" class="btn-primary py-2 px-5 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Input Hafalan Baru
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="table-th text-center w-12">No</th>
                    <th class="table-th">Siswa</th>
                    <th class="table-th">Surah & Ayat</th>
                    <th class="table-th text-center">Tgl Setor</th>
                    <th class="table-th text-center">Semester</th>
                    <th class="table-th text-center">Status Evaluasi</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($hafalan as $index => $item)
                    <tr class="hover:bg-gray-50/70 transition duration-150">
                        <td class="table-td text-center text-gray-400 text-sm">{{ $hafalan->firstItem() + $index }}</td>
                        <td class="table-td">
                            <span class="font-bold text-gray-800 line-clamp-1" title="{{ $item->siswa->user->nama_lengkap ?? 'Unknown' }}">
                                {{ $item->siswa->user->nama_lengkap ?? 'Unknown' }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $item->siswa->nisn ?? '-' }}</span>
                        </td>
                        <td class="table-td">
                            <span class="font-bold text-teal-700">{{ $item->nama_surah }}</span>
                            <br>
                            <span class="text-xs text-gray-500">{{ $item->jumlah_ayat }} Ayat</span>
                        </td>
                        <td class="table-td text-center text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tanggal_input)->translatedFormat('d M Y') }}
                        </td>
                        <td class="table-td text-center">
                            <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-1 rounded-full">
                                {{ $item->periode_semester ?? '-' }}
                            </span>
                        </td>
                        <td class="table-td text-center">
                            @if($item->nilaiEvaluasi)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-green-700 bg-green-100 px-2 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sudah
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Belum
                                </span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('guru.hafalan.show', $item->id) }}" class="btn-secondary px-2 py-1 text-xs" title="Detail / Evaluasi">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('guru.hafalan.edit', $item->id) }}" class="btn-secondary px-2 py-1 text-xs" title="Edit">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('guru.hafalan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data hafalan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary px-2 py-1 text-xs hover:bg-red-50" title="Hapus">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-16 text-center">
                                <p class="text-5xl mb-3">📖</p>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada data hafalan</p>
                                <p class="text-gray-400 text-sm mt-1">Data setoran hafalan siswa yang Anda input akan tampil di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-bold text-gray-700">{{ $hafalan->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-gray-700">{{ $hafalan->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-700">{{ $hafalan->total() }}</span> data
    </div>
    @if($hafalan->hasPages())
        <div>
            {{ $hafalan->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
