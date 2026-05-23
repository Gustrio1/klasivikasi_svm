@extends('layouts.app')

@section('title', 'Data Training SVM (Admin)')

@section('content')

<x-page-header
    title="Dataset Pelatihan (SVM)"
    subtitle="Kelola dataset fitur hafalan yang digunakan untuk melatih model klasifikasi. Fitur: Total Surat/Semester, Usia, dan Media."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'SVM & Model', 'url' => null],
        ['label' => 'Data Training', 'url' => null],
    ]"
>
    <div class="flex gap-3">
        <a href="{{ route('admin.data-training.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Manual
        </a>
    </div>
</x-page-header>

{{-- Statistik Dataset --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
    @php
        $totalLulus = \App\Models\DataTraining::where('label_kelas', 'Lulus')->count();
        $totalTidakLulus = \App\Models\DataTraining::where('label_kelas', 'Tidak Lulus')->count();
    @endphp
    <div class="card p-5 bg-white flex items-center gap-4 border-none shadow-lg">
        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Sampel</p>
            <p class="text-2xl font-black text-gray-800">{{ $dataTrainings->total() }}</p>
        </div>
    </div>
    <div class="card p-5 bg-white flex items-center gap-4 border-none shadow-lg border-l-4 border-l-emerald-400">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-black text-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lulus</p>
            <p class="text-2xl font-black text-emerald-600">{{ $totalLulus }}</p>
        </div>
    </div>
    <div class="card p-5 bg-white flex items-center gap-4 border-none shadow-lg border-l-4 border-l-rose-400">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center font-black text-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tidak Lulus</p>
            <p class="text-2xl font-black text-rose-600">{{ $totalTidakLulus }}</p>
        </div>
    </div>
</div>

<div class="card p-0 overflow-hidden border-none shadow-xl">
    <div class="p-5 bg-white border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="font-black text-gray-800 flex items-center gap-2 text-sm">
            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Rincian Fitur Dataset
        </h3>
        <form action="{{ route('admin.data-training.index') }}" method="GET" class="flex gap-2">
            <select name="label_kelas" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold focus:ring-teal-500 focus:border-teal-500" onchange="this.form.submit()">
                <option value="">Semua Label</option>
                <option value="Lulus" {{ request('label_kelas') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="Tidak Lulus" {{ request('label_kelas') == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
            </select>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10 text-center">No</th>
                    <th class="table-th text-center">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Total Surat
                        </div>
                    </th>
                    <th class="table-th text-center">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Usia (Thn)
                        </div>
                    </th>
                    <th class="table-th">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Media Hafalan
                        </div>
                    </th>
                    <th class="table-th text-center">Label</th>
                    <th class="table-th">Sumber</th>
                    <th class="table-th text-center w-16">Valid</th>
                    <th class="table-th text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dataTrainings as $dt)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400 text-sm">{{ $dataTrainings->firstItem() + $loop->index }}</td>
                        
                        {{-- Total Surat --}}
                        <td class="table-td text-center">
                            <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg text-sm">
                                {{ $dt->fitur_total_surah }} surat
                                @if($dt->fitur_total_surah >= 30)
                                    <span class="ml-1 text-emerald-500" title="Memenuhi syarat lulus">✔</span>
                                @else
                                    <span class="ml-1 text-rose-400" title="Belum memenuhi syarat lulus">✗</span>
                                @endif
                            </span>
                        </td>
                        
                        {{-- Usia --}}
                        <td class="table-td text-center">
                            <span class="font-mono font-bold text-gray-700">
                                {{ $dt->fitur_usia }} <span class="text-[10px] text-gray-400 font-normal">thn</span>
                            </span>
                        </td>
                        
                        {{-- Media --}}
                        <td class="table-td">
                            @if($dt->mediaHafalan)
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $dt->mediaHafalan->nama_media }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        {{ ucfirst($dt->mediaHafalan->jenis_media) }}
                                    </p>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">—</span>
                            @endif
                        </td>
                        
                        {{-- Label Kelas --}}
                        <td class="table-td text-center">
                            @if($dt->label_kelas === 'Lulus')
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest border border-emerald-200">LULUS</span>
                            @else
                                <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest border border-rose-200">TIDAK LULUS</span>
                            @endif
                        </td>
                        
                        {{-- Sumber --}}
                        <td class="table-td text-xs text-gray-500 italic">{{ $dt->sumber_data ?? 'Manual' }}</td>
                        
                        {{-- Valid --}}
                        <td class="table-td text-center">
                            @if($dt->is_valid)
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full inline-flex items-center justify-center" title="Tervalidasi">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            @else
                                <span class="w-6 h-6 bg-gray-100 text-gray-400 rounded-full inline-flex items-center justify-center" title="Belum Valid">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            @endif
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.data-training.edit', $dt->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.data-training.destroy', $dt->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data training ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-20 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium">Belum ada dataset.</p>
                            <p class="text-gray-300 text-xs mt-1">Tambahkan data secara manual untuk mulai melatih model SVM.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dataTrainings->hasPages())
        <div class="p-4 bg-gray-50/50 border-t border-gray-100">
            {{ $dataTrainings->links() }}
        </div>
    @endif
</div>

@endsection
