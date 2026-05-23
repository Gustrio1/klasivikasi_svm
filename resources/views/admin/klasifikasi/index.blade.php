@extends('layouts.app')

@section('title', 'Riwayat Klasifikasi (Admin)')

@section('content')

<x-page-header
    title="Seluruh Hasil Klasifikasi SVM"
    subtitle="Pantau performa klasifikasi otomatis untuk seluruh siswa di sistem."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hasil Klasifikasi', 'url' => null],
    ]"
/>

{{-- Ringkasan --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="card text-center py-4 bg-white border-l-4 border-l-indigo-500">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Total</p>
        <p class="text-2xl font-black text-gray-800">{{ $ringkasan['total'] }}</p>
    </div>
    <div class="card text-center py-4 bg-emerald-50 border-emerald-200 border-l-4 border-l-emerald-500">
        <p class="text-xs text-emerald-600 font-bold uppercase tracking-widest mb-1">Lulus</p>
        <p class="text-2xl font-black text-emerald-700">{{ $ringkasan['Lulus'] }}</p>
    </div>
    <div class="card text-center py-4 bg-red-50 border-red-200 border-l-4 border-l-red-500">
        <p class="text-xs text-red-600 font-bold uppercase tracking-widest mb-1">Tidak Lulus</p>
        <p class="text-2xl font-black text-red-700">{{ $ringkasan['Tidak Lulus'] }}</p>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Siswa</th>
                    <th class="table-th text-left">Semester</th>
                    <th class="table-th text-center">Total Surat</th>
                    <th class="table-th text-center">Modus Media</th>
                    <th class="table-th text-center">Hasil (Kelas)</th>
                    <th class="table-th text-center">Confidence</th>
                    <th class="table-th text-center w-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($hasilKlasifikasi as $hasil)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">
                            {{ $hasilKlasifikasi->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $hasil->siswa->user->nama_lengkap ?? '-' }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest">Guru: {{ $hasil->siswa->guru->user->nama_lengkap ?? '-' }}</div>
                        </td>
                        <td class="table-td text-sm font-semibold text-teal-700">
                            {{ $hasil->periode_semester }}
                        </td>
                        <td class="table-td text-center text-sm font-bold text-gray-600">
                            {{ $hasil->total_surah }}
                        </td>
                        <td class="table-td text-center">
                            @php
                                $vector = is_array($hasil->vector_svm) ? $hasil->vector_svm : json_decode($hasil->vector_svm, true);
                                $id_media = $vector['id_media'] ?? null;
                                $media = $id_media ? \App\Models\MediaHafalan::find($id_media) : null;
                            @endphp
                            <span class="text-xs text-gray-500">{{ $media ? $media->nama_media : '-' }}</span>
                        </td>
                        <td class="table-td text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full font-black text-white text-[10px] tracking-widest shadow-sm
                                {{ $hasil->kelas_prediksi == 'Lulus' ? 'bg-emerald-500' : 'bg-red-500' }}">
                                {{ strtoupper($hasil->kelas_prediksi) }}
                            </span>
                        </td>
                        <td class="table-td text-center">
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $hasil->confidence_score * 100 }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-600">{{ number_format($hasil->confidence_score * 100, 1) }}%</span>
                            </div>
                        </td>
                        <td class="table-td text-center">
                            <a href="{{ route('admin.hasil-klasifikasi.show', $hasil->id) }}" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">Belum ada data klasifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($hasilKlasifikasi->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $hasilKlasifikasi->links() }}
        </div>
    @endif
</div>

@endsection
