@extends('layouts.app')

@section('title', 'Laporan Pencapaian')

@php
    $rolePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'guru.';
@endphp

@section('content')

<x-page-header
    title="Riwayat Cetak Laporan"
    subtitle="Arsip dokumen laporan perkembangan siswa yang telah digenerasi sistem"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan', 'url' => null],
    ]"
>
    @if(auth()->user()->role !== 'admin')
    <a href="{{ route($rolePrefix . 'laporan.create') }}" class="btn-primary py-2 px-4 shadow-sm text-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Laporan Baru
    </a>
    @endif
</x-page-header>

<div class="card p-0 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Daftar Dokumen PDF</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Detail Laporan</th>
                    <th class="table-th text-left">Siswa & Guru</th>
                    <th class="table-th text-center">Tanggal Cetak</th>
                    <th class="table-th text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($laporan as $lap)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="table-td text-center text-gray-500">
                            {{ $laporan->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $lap->judul_laporan }}</div>
                            <div class="text-xs text-indigo-600 mt-0.5">Periode: {{ $lap->periode }}</div>
                        </td>
                        <td class="table-td">
                            <div class="text-sm font-semibold text-gray-700">Siswa: {{ $lap->siswa->user->nama_lengkap ?? '-' }}</div>
                            <div class="text-[11px] text-gray-500">Guru: {{ $lap->guru->user->nama_lengkap ?? '-' }}</div>
                        </td>
                        <td class="table-td text-center">
                            <div class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($lap->tanggal_cetak)->format('d M Y') }}</div>
                            <div class="text-[11px] text-gray-400">{{ \Carbon\Carbon::parse($lap->tanggal_cetak)->format('H:i') }} WIB</div>
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route($rolePrefix . 'laporan.download', $lap->id) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 rounded-lg transition" title="Preview / Download PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </a>
                                @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'guru' && auth()->user()->guru->id == $lap->id_guru))
                                    <form action="{{ route($rolePrefix . 'laporan.destroy', $lap->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file laporan ini? Data historis ini tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus Laporan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="font-medium">Belum ada dokumen laporan PDF yang pernah dibuat.</p>
                            @if(auth()->user()->role !== 'admin')
                            <a href="{{ route($rolePrefix . 'laporan.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block font-medium">Buat laporan pencapaian pertama sekarang</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($laporan->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $laporan->links() }}
        </div>
    @endif
    
</div>

@endsection
