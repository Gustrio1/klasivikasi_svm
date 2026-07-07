@extends('layouts.app')

@section('title', 'Detail Log Evaluasi (Admin)')

@section('content')

<x-page-header
    title="Analisis Evaluasi Model"
    subtitle="Audit metrik performa mendalam dan Confusion Matrix untuk versi: {{ $logEvaluasiModel->modelSvm?->versi_model ?? 'Model Tidak Ditemukan (Dihapus)' }}."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Log Evaluasi', 'url' => route('admin.log-evaluasi.index')],
        ['label' => 'Audit Detail', 'url' => null],
    ]"
/>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-12">
    {{-- Metrics Card --}}
    <div class="space-y-6">
        <div class="card p-8 border-none shadow-xl bg-white">
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-8 flex items-center gap-2">
                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                Classification Metrics
            </h4>
            
            <div class="grid grid-cols-2 gap-12">
                <div class="space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Accuracy</p>
                    <p class="text-4xl font-black text-gray-800">{{ number_format($logEvaluasiModel->akurasi, 2) }}%</p>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500" style="width: {{ $logEvaluasiModel->akurasi }}%"></div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">F1-Score</p>
                    <p class="text-4xl font-black text-teal-600">{{ number_format($logEvaluasiModel->f1_score, 3) }}</p>
                    <p class="text-[10px] text-gray-400 italic leading-tight">Harmonic mean of precision & recall</p>
                </div>

                <div class="space-y-2 border-t border-gray-50 pt-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Precision</p>
                    <p class="text-3xl font-black text-gray-700">{{ number_format($logEvaluasiModel->precision, 3) }}</p>
                </div>

                <div class="space-y-2 border-t border-gray-50 pt-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Recall</p>
                    <p class="text-3xl font-black text-gray-700">{{ number_format($logEvaluasiModel->recall, 3) }}</p>
                </div>
            </div>
            
            <div class="mt-12 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Waktu Evaluasi</p>
                        <p class="text-sm font-bold text-gray-700">{{ $logEvaluasiModel->tanggal_evaluasi?->format('d F Y - H:i') ?? '-' }} WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Confusion Matrix --}}
    <div>
        <div class="card p-8 border-none shadow-xl bg-white h-full">
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-8 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                Confusion Matrix
            </h4>
            
            <div class="relative overflow-hidden rounded-2xl border border-gray-100">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-4 border border-gray-100 text-[10px] font-black uppercase text-gray-400">Pred \ Act</th>
                            @foreach($labels as $label)
                                <th class="p-4 border border-gray-100 text-sm font-black text-gray-700 bg-gray-50/50">Kelas {{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labels as $i => $rowLabel)
                            <tr>
                                <td class="p-4 border border-gray-100 text-sm font-black text-gray-700 bg-gray-50/50 text-left">Kelas {{ $rowLabel }}</td>
                                @foreach($labels as $j => $colLabel)
                                    @php
                                        $value = $confusionMatrix[$i][$j] ?? 0;
                                        $isDiagonal = $i == $j;
                                    @endphp
                                    <td class="p-6 border border-gray-100 font-mono text-lg font-black {{ $isDiagonal ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50/30 text-rose-300' }}">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex items-center gap-6 justify-center text-[10px] font-bold uppercase tracking-widest">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-emerald-100 rounded"></div>
                    <span class="text-emerald-700">Correct Predictions</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-rose-50 rounded border border-rose-100"></div>
                    <span class="text-rose-300">Errors / Misclassified</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
