@extends('layouts.app')

@section('title', 'Manajemen Model SVM (Admin)')

@section('content')

<x-page-header
    title="Model Klasifikasi SVM"
    subtitle="Kelola versi model, parameter hyperparameter, dan aktivasi model produksi."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'SVM & Model', 'url' => null],
        ['label' => 'Model SVM', 'url' => null],
    ]"
>
    <a href="{{ route('admin.model-svm.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Register Model Baru
    </a>
</x-page-header>

<div class="space-y-6 pb-12">
    @forelse($models as $m)
        <div class="card overflow-hidden border-none shadow-xl hover:shadow-2xl transition duration-300 {{ $m->is_active ? 'ring-2 ring-teal-500' : '' }}">
            <div class="flex flex-col md:flex-row">
                {{-- Status & Versi --}}
                <div class="md:w-64 p-6 flex flex-col items-center justify-center text-center border-b md:border-b-0 md:border-r border-gray-100 {{ $m->is_active ? 'bg-teal-50/30' : 'bg-gray-50/30' }}">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3 {{ $m->is_active ? 'bg-teal-100 text-teal-600' : 'bg-gray-200 text-gray-400' }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h4 class="text-lg font-black text-gray-800">{{ $m->versi_model }}</h4>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Versi Model</p>
                    
                    @if($m->is_active)
                        <span class="mt-4 px-3 py-1 bg-teal-600 text-white text-[10px] font-black rounded-full shadow-lg shadow-teal-200 uppercase tracking-widest">AKTIF (PRODUKSI)</span>
                    @else
                        <form action="{{ route('admin.model-svm.aktivasi', $m->id) }}" method="POST" class="mt-4 w-full px-4">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-white border border-gray-200 text-gray-600 hover:border-teal-500 hover:text-teal-600 font-bold text-xs rounded-lg transition shadow-sm">Aktifkan Model</button>
                        </form>
                    @endif
                </div>

                {{-- Detail Parameter & Performa --}}
                <div class="flex-1 p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Akurasi Validasi</p>
                        <p class="text-2xl font-black text-teal-600">{{ number_format($m->akurasi_model, 2) }}%</p>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden mt-2">
                            <div class="bg-teal-500 h-full" style="width: {{ $m->akurasi_model }}%"></div>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Kernel Type</p>
                        <p class="text-xl font-bold text-gray-700 uppercase tracking-tighter">{{ $m->kernel_type }}</p>
                        <p class="text-[10px] text-gray-400 italic">Mendukung non-linear</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Parameter C / Gamma</p>
                        <div class="flex items-center gap-2">
                            <div class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-mono font-bold rounded">C: {{ $m->parameter_C }}</div>
                            <div class="px-2 py-1 bg-violet-50 text-violet-700 text-xs font-mono font-bold rounded">G: {{ $m->parameter_gamma }}</div>
                        </div>
                    </div>

                    <div class="space-y-1 flex flex-col justify-between items-end">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase">Tanggal Training</p>
                            <p class="text-xs font-bold text-gray-600">{{ $m->tanggal_training->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.model-svm.show', $m->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail Metrics">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </a>
                            @if(!$m->is_active)
                                <form action="{{ route('admin.model-svm.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus versi model ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card p-12 text-center text-gray-400 italic">
            Belum ada model yang di-training. Silakan buat model baru.
        </div>
    @endforelse

    <div class="mt-6">
        {{ $models->links() }}
    </div>
</div>

@endsection
