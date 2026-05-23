@extends('layouts.app')

@section('title', 'Log Evaluasi Model (Admin)')

@section('content')

<x-page-header
    title="Riwayat Evaluasi Model"
    subtitle="Audit performa model SVM berdasarkan metrik klasifikasi (Precision, Recall, F1)."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'SVM & Model', 'url' => null],
        ['label' => 'Log Evaluasi', 'url' => null],
    ]"
/>

<div class="card p-0 overflow-hidden border-none shadow-xl">
    <div class="p-6 bg-white border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-black text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Rekaman Performa Model
        </h3>
        <form action="{{ route('admin.log-evaluasi.index') }}" method="GET" class="flex gap-2">
            <select name="id_model" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-bold" onchange="this.form.submit()">
                <option value="">Semua Model</option>
                @foreach($models as $m)
                    <option value="{{ $m->id }}" {{ request('id_model') == $m->id ? 'selected' : '' }}>{{ $m->versi_model }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="table-th w-10">No</th>
                    <th class="table-th">Versi Model</th>
                    <th class="table-th text-center">Akurasi</th>
                    <th class="table-th text-center">Precision</th>
                    <th class="table-th text-center">Recall</th>
                    <th class="table-th text-center">F1 Score</th>
                    <th class="table-th text-center">Tanggal</th>
                    <th class="table-th text-center w-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td text-center text-gray-400">{{ $logs->firstItem() + $loop->index }}</td>
                        <td class="table-td">
                            <div class="font-bold text-gray-800">{{ $log->modelSvm->versi_model }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $log->modelSvm->kernel_type }} kernel</div>
                        </td>
                        <td class="table-td text-center">
                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-lg font-mono font-black text-xs border border-green-100">{{ number_format($log->akurasi, 2) }}%</span>
                        </td>
                        <td class="table-td text-center font-mono text-gray-600">{{ number_format($log->precision, 3) }}</td>
                        <td class="table-td text-center font-mono text-gray-600">{{ number_format($log->recall, 3) }}</td>
                        <td class="table-td text-center font-mono text-gray-600 font-black text-indigo-600">{{ number_format($log->f1_score, 3) }}</td>
                        <td class="table-td text-center text-xs text-gray-500">{{ $log->tanggal_evaluasi->format('d/m/Y H:i') }}</td>
                        <td class="table-td text-center">
                            <a href="{{ route('admin.log-evaluasi.show', $log->id) }}" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition" title="Lihat Detail & Confusion Matrix">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">Belum ada riwayat evaluasi model.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="p-4 bg-gray-50/50 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection
