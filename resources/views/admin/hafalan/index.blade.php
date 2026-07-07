@extends('layouts.app')

@section('title', 'Data Hafalan Siswa')

@section('content')

<x-page-header
    title="Data Hafalan Siswa"
    subtitle="Pantau seluruh riwayat setoran hafalan siswa dalam sistem"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hafalan', 'url' => null],
    ]"
/>

<div class="mb-5 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div class="flex-1 w-full">
        <form method="GET" action="{{ route('admin.hafalan.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." class="form-input w-full md:w-auto md:min-w-[200px]">
            
            <input type="text" name="surah" value="{{ request('surah') }}" placeholder="Nama Surah" class="form-input w-full md:w-32">
            
            <select name="kelas" class="form-input w-full md:w-36">
                <option value="">Semua Kelas</option>
                <option value="A" {{ request('kelas') == 'A' ? 'selected' : '' }}>Kelas A</option>
                <option value="B" {{ request('kelas') == 'B' ? 'selected' : '' }}>Kelas B</option>
                <option value="C" {{ request('kelas') == 'C' ? 'selected' : '' }}>Kelas C</option>
            </select>
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input w-full md:w-36" title="Tanggal Awal">
                <span class="text-gray-400">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input w-full md:w-36" title="Tanggal Akhir">
            </div>

            <button type="submit" class="btn-primary py-2 px-5">Filter</button>
            
            @if(request('search') || request('surah') || request('kelas') || request('start_date') || request('end_date'))
                <a href="{{ route('admin.hafalan.index') }}" class="btn-secondary py-2 px-4 hover:bg-gray-100">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="table-th text-center w-12">No</th>
                    <th class="table-th">Siswa & Guru Pengampu</th>
                    <th class="table-th">Surah</th>
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
                            <div class="flex flex-col text-xs text-gray-500 mt-0.5">
                                <span>NISN: {{ $item->siswa->nisn ?? '-' }}</span>
                                <span class="text-teal-700 font-medium mt-0.5">Guru: {{ $item->guru->user->nama_lengkap ?? '-' }}</span>
                            </div>
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
                                    Sudah Dinilai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Belum Dinilai
                                </span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center">
                                <a href="{{ route('admin.hafalan.show', $item->id) }}" class="btn-secondary px-3 py-1.5 text-xs flex items-center gap-1 hover:bg-teal-50 hover:text-teal-700" title="Detail Hafalan">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-16 text-center">
                                <p class="text-5xl mb-3">📖</p>
                                <p class="text-gray-500 font-semibold text-lg">Belum ada data hafalan</p>
                                <p class="text-gray-400 text-sm mt-1">Data setoran hafalan siswa dalam sistem akan tampil di sini.</p>
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
