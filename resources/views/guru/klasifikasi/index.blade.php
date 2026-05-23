@extends('layouts.app')

@section('title', 'Hasil Klasifikasi Siswa')

@section('content')

<x-page-header
    title="Data Klasifikasi SVM"
    subtitle="Pantau hasil klasifikasi Lulus / Tidak Lulus untuk siswa bimbingan Anda."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hasil Klasifikasi', 'url' => null],
    ]"
/>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-indigo-500 bg-white shadow-sm flex flex-col justify-center">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Klasifikasi</p>
        <p class="text-2xl font-black text-gray-800">{{ $ringkasan['total'] ?? 0 }}</p>
    </div>
    <div class="card p-4 border-l-4 border-l-emerald-500 bg-emerald-50 shadow-sm flex flex-col justify-center">
        <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider">✅ Lulus</p>
        <p class="text-2xl font-black text-emerald-700">{{ $ringkasan['Lulus'] ?? 0 }}</p>
    </div>
    <div class="card p-4 border-l-4 border-l-red-500 bg-red-50 shadow-sm flex flex-col justify-center">
        <p class="text-xs text-red-600 font-bold uppercase tracking-wider">❌ Tidak Lulus</p>
        <p class="text-2xl font-black text-red-700">{{ $ringkasan['Tidak Lulus'] ?? 0 }}</p>
    </div>
</div>

{{-- Data Table Module --}}
<div class="card p-0 overflow-hidden">
    
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-800">Riwayat Klasifikasi</h3>
        <div class="flex gap-2">
           {{-- Future filters could go here --}}
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-left">Nama Siswa</th>
                    <th class="table-th text-left">Semester</th>
                    <th class="table-th text-center">Total Surat</th>
                    <th class="table-th text-left">Modus Media</th>
                    <th class="table-th text-center">Hasil Klasifikasi</th>
                    <th class="table-th text-center">Tanggal Proses</th>
                    <th class="table-th text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($hasilKlasifikasi as $klasifikasi)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="table-td text-center text-gray-500">
                            {{ $hasilKlasifikasi->firstItem() + $loop->index }}
                        </td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $klasifikasi->siswa->user->nama_lengkap ?? 'Anonim' }}</div>
                            <div class="text-xs text-gray-500 uppercase tracking-widest">{{ $klasifikasi->siswa->nisn ?? '-' }}</div>
                        </td>
                        <td class="table-td">
                            <div class="text-sm font-semibold text-teal-700">{{ $klasifikasi->periode_semester }}</div>
                        </td>
                        <td class="table-td text-center">
                            <span class="font-bold text-gray-800">{{ $klasifikasi->total_surah }}</span>
                            <span class="text-xs text-gray-400 ml-1">Surat</span>
                        </td>
                        <td class="table-td">
                            @php
                                $vector = is_array($klasifikasi->vector_svm) ? $klasifikasi->vector_svm : json_decode($klasifikasi->vector_svm, true);
                                $id_media = $vector['id_media'] ?? null;
                                $media = $id_media ? \App\Models\MediaHafalan::find($id_media) : null;
                            @endphp
                            <span class="text-sm text-gray-700">{{ $media ? $media->nama_media : '-' }}</span>
                        </td>
                        <td class="table-td text-center">
                            @php
                                $kelas = $klasifikasi->kelas_prediksi;
                                $lulus = $kelas === 'Lulus';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                {{ $lulus ? '✅ Lulus' : '❌ Tidak Lulus' }}
                            </span>
                        </td>
                        <td class="table-td text-center">
                            <div class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($klasifikasi->tanggal_klasifikasi)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($klasifikasi->tanggal_klasifikasi)->format('H:i') }} WIB</div>
                        </td>
                        <td class="table-td text-center">
                            <a href="{{ route('guru.siswa.show', $klasifikasi->id_siswa) }}" class="inline-flex items-center justify-center p-2 text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition" title="Lihat Profil Siswa">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-400">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="font-medium">Belum ada data klasifikasi.</p>
                            <p class="text-sm mt-1">Lakukan evaluasi dan sinkronisasi SVM di menu Hafalan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($hasilKlasifikasi->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $hasilKlasifikasi->links() }}
        </div>
    @endif
    
</div>

@endsection
