@extends('layouts.app')

@section('title', 'Hasil Klasifikasi Saya')

@section('content')

<x-page-header
    title="Hasil Klasifikasi Saya"
    subtitle="Riwayat prediksi kelas hafalan berdasarkan model SVM"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hasil Klasifikasi', 'url' => null],
    ]"
/>

{{-- ── Kartu Ringkasan ─────────────────────────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card text-center py-4">
        <p class="text-2xl font-bold text-gray-800">{{ $ringkasan['total'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Klasifikasi</p>
    </div>
    <div class="card text-center py-4 border-green-200">
        <p class="text-2xl font-bold text-green-600">{{ $ringkasan['Lulus'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Lulus ✅</p>
    </div>
    <div class="card text-center py-4 border-red-200">
        <p class="text-2xl font-bold text-red-500">{{ $ringkasan['Tidak Lulus'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Tidak Lulus ❌</p>
    </div>
</div>

{{-- ── Tabel Hasil Klasifikasi ──────────────────────────── --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Semester</th>
                    <th class="table-th">Total Surat</th>
                    <th class="table-th">Modus Media</th>
                    <th class="table-th">Tgl Klasifikasi</th>
                    <th class="table-th">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($hasilKlasifikasi as $i => $hasil)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-gray-400">{{ $hasilKlasifikasi->firstItem() + $i }}</td>
                        <td class="table-td font-medium text-gray-800">{{ $hasil->periode_semester ?? '-' }}</td>
                        <td class="table-td text-gray-700 font-semibold">
                            {{ $hasil->total_surah ?? '-' }} Surat
                        </td>
                        <td class="table-td text-gray-600">
                            @php
                                $vector = is_array($hasil->vector_svm) ? $hasil->vector_svm : json_decode($hasil->vector_svm, true);
                                $id_media = $vector['id_media'] ?? null;
                                $media = $id_media ? \App\Models\MediaHafalan::find($id_media) : null;
                            @endphp
                            {{ $media ? $media->nama_media : '-' }}
                        </td>
                        <td class="table-td text-gray-500 text-sm">
                            {{ \Carbon\Carbon::parse($hasil->tanggal_klasifikasi)->translatedFormat('d M Y') }}
                        </td>
                        <td class="table-td">
                            <a href="{{ route('siswa.hasil-klasifikasi.show', $hasil->id) }}"
                               class="btn-secondary py-1.5 px-3 text-xs">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-16 text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">Belum ada hasil klasifikasi</p>
                                <p class="text-gray-400 text-sm mt-1">Hafalan perlu diinput dan diproses oleh sistem SVM terlebih dahulu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hasilKlasifikasi->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $hasilKlasifikasi->links() }}
        </div>
    @endif
</div>

@endsection
