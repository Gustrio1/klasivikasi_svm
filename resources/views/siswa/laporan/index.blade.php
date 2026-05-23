@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')

<x-page-header
    title="Laporan Saya"
    subtitle="Daftar laporan kemajuan hafalan yang telah digenerate"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Laporan Saya', 'url' => null],
    ]"
/>

<div class="card">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Judul Laporan</th>
                    <th class="table-th">Periode</th>
                    <th class="table-th">Tanggal Cetak</th>
                    <th class="table-th">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($laporan as $i => $lap)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-gray-400">{{ $laporan->firstItem() + $i }}</td>
                        <td class="table-td">
                            <p class="font-medium text-gray-800">{{ $lap->judul_laporan }}</p>
                        </td>
                        <td class="table-td text-gray-500">{{ $lap->periode }}</td>
                        <td class="table-td text-gray-500">
                            {{ \Carbon\Carbon::parse($lap->tanggal_cetak)->translatedFormat('d F Y') }}
                        </td>
                        <td class="table-td">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('siswa.laporan.show', $lap->id) }}"
                                   class="btn-secondary py-1.5 px-3 text-xs">Lihat</a>
                                <a href="{{ route('siswa.laporan.download', $lap->id) }}"
                                   class="btn-primary py-1.5 px-3 text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Belum ada laporan</p>
                                <p class="text-gray-400 text-sm mt-1">Laporan akan tersedia setelah guru atau admin membuatnya</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($laporan->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $laporan->links() }}
        </div>
    @endif
</div>

@endsection
