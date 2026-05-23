@extends('layouts.app')

@section('title', 'Detail Model SVM (Admin)')

@section('content')

<x-page-header
    title="Detail Model Performa"
    subtitle="Analisis mendalam terhadap versi model: {{ $modelSvm->versi_model }}."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Model SVM', 'url' => route('admin.model-svm.index')],
        ['label' => 'Detail Model', 'url' => null],
    ]"
/>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-12">
    {{-- Info Utama --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6 border-none shadow-xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-60">Status Produksi</p>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-2xl font-black">{{ $modelSvm->is_active ? 'AKTIF' : 'NON-AKTIF' }}</span>
                @if($modelSvm->is_active)
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                        <div class="w-4 h-4 bg-white rounded-full"></div>
                    </div>
                @endif
            </div>
            
            <hr class="my-6 border-white/10">
            
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="text-[10px] uppercase font-bold opacity-60">Accuracy</p>
                    <p class="text-xl font-black">{{ number_format($modelSvm->akurasi_model, 2) }}%</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold opacity-60">Kernel</p>
                    <p class="text-xl font-black uppercase">{{ $modelSvm->kernel_type }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 border-none shadow-xl">
             <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-4">Hyperparameters</h4>
             <div class="space-y-4">
                 <div class="flex justify-between items-center text-sm">
                     <span class="text-gray-400">Regularization (C)</span>
                     <span class="font-mono font-bold text-gray-800">{{ $modelSvm->parameter_C }}</span>
                 </div>
                 <div class="flex justify-between items-center text-sm">
                     <span class="text-gray-400">Gamma (RBF Only)</span>
                     <span class="font-mono font-bold text-gray-800">{{ $modelSvm->parameter_gamma }}</span>
                 </div>
                 <div class="flex justify-between items-center text-sm">
                     <span class="text-gray-400">Training Date</span>
                     <span class="font-bold text-gray-800">{{ $modelSvm->tanggal_training->format('d M Y') }}</span>
                 </div>
             </div>
        </div>
    </div>

    {{-- Log Evaluasi Terakhir --}}
    <div class="lg:col-span-2">
        <div class="card p-0 overflow-hidden border-none shadow-xl">
            <div class="p-6 bg-white border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-black text-gray-800 flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Histori Evaluasi Metrics
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-base w-full">
                    <thead class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest">
                        <tr>
                            <th class="table-th text-center">Acc</th>
                            <th class="table-th text-center">Prec</th>
                            <th class="table-th text-center">Recall</th>
                            <th class="table-th text-center">F1</th>
                            <th class="table-th text-center">Waktu</th>
                            <th class="table-th"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($modelSvm->logEvaluasiModels as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="table-td text-center font-bold text-teal-600">{{ number_format($log->akurasi, 1) }}%</td>
                                <td class="table-td text-center font-mono text-gray-600">{{ number_format($log->precision, 3) }}</td>
                                <td class="table-td text-center font-mono text-gray-600">{{ number_format($log->recall, 3) }}</td>
                                <td class="table-td text-center font-mono font-black text-indigo-600">{{ number_format($log->f1_score, 3) }}</td>
                                <td class="table-td text-center text-[10px] text-gray-400">{{ $log->tanggal_evaluasi->format('d/m/y H:i') }}</td>
                                <td class="table-td text-center">
                                    <a href="{{ route('admin.log-evaluasi.show', $log->id) }}" class="text-gray-400 hover:text-indigo-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 italic text-sm">Belum ada log evaluasi untuk model ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
