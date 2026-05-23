@extends('layouts.app')

@section('title', 'Laporan Hafalan (Guru)')

@section('content')

<x-page-header
    title="Laporan Hasil Bimbingan"
    subtitle="Kelola dan cetak laporan hafalan siswa yang Anda bimbing."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan', 'url' => null],
    ]"
>
    <a href="{{ route('guru.laporan.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Generate Laporan PDF
    </a>
</x-page-header>

<div class="card p-0 overflow-hidden border-none shadow-xl">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th">Judul Laporan</th>
                    <th class="table-th text-left">Nama Siswa</th>
                    <th class="table-th text-center">Periode</th>
                    <th class="table-th text-center">Tanggal Cetak</th>
                    <th class="table-th text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($laporan as $l)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">{{ $laporan->firstItem() + $loop->index }}</td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $l->judul_laporan }}</div>
                        </td>
                        <td class="table-td">
                            <div class="text-sm font-medium text-gray-700">{{ $l->siswa->user->nama_lengkap }}</div>
                            <div class="text-[10px] text-gray-400 font-mono">{{ $l->siswa->nisn ?? '-' }}</div>
                        </td>
                        <td class="table-td text-center text-sm text-gray-600">{{ $l->periode }}</td>
                        <td class="table-td text-center text-xs text-gray-500">{{ $l->tanggal_cetak->format('d/m/Y H:i') }}</td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('guru.laporan.download', $l->id) }}" class="p-1.5 text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Lihat PDF" target="_blank">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h1a1 1 0 110 2H9V9z M9 13h6m-6 4h6"/></svg>
                                </a>
                                <form action="{{ route('guru.laporan.destroy', $l->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus laporan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic">Belum ada riwayat laporan yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($laporan->hasPages())
        <div class="p-4 bg-gray-50/50 border-t border-gray-100">
            {{ $laporan->links() }}
        </div>
    @endif
</div>

@endsection
