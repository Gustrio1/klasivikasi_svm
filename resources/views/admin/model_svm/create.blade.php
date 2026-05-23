@extends('layouts.app')

@section('title', 'Register Model SVM (Admin)')

@section('content')

<x-page-header
    title="Registrasi Model Baru"
    subtitle="Simpan parameter dan metrik akurasi dari hasil training model SVM di server."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Model SVM', 'url' => route('admin.model-svm.index')],
        ['label' => 'Register Model', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card p-8 border-none shadow-2xl">
        <form action="{{ route('admin.model-svm.store') }}" method="POST">
            @csrf
            
            <div class="space-y-8">
                {{-- Identitas Model --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input-group label="Versi Model" icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        <input type="text" name="versi_model" value="{{ old('versi_model', 'v1.0') }}" class="input-base" required placeholder="Contoh: v1.0.2-Stable">
                    </x-input-group>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700">Akurasi Model (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="akurasi_model" value="{{ old('akurasi_model', 0) }}" class="input-base pr-12" required min="0" max="100">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Hyperparameters --}}
                <div>
                    <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                        SVM Hyperparameters
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Kernel Type</label>
                            <select name="kernel_type" class="input-base" required>
                                <option value="rbf" {{ old('kernel_type') == 'rbf' ? 'selected' : '' }}>RBF (Recommended)</option>
                                <option value="linear" {{ old('kernel_type') == 'linear' ? 'selected' : '' }}>Linear</option>
                            </select>
                        </div>

                        <x-input-group label="Parameter C" icon="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            <input type="number" step="0.001" name="parameter_C" value="{{ old('parameter_C', 1.0) }}" class="input-base" required>
                        </x-input-group>

                        <x-input-group label="Parameter Gamma" icon="M13 10V3L4 14h7v7l9-11h-7z">
                            <input type="number" step="0.001" name="parameter_gamma" value="{{ old('parameter_gamma', 0.1) }}" class="input-base" required>
                        </x-input-group>
                    </div>
                </div>

                <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100">
                    <p class="text-xs text-teal-700 leading-relaxed font-medium">
                        <strong>Catatan:</strong> Model baru yang didaftarkan akan berstatus <strong>Tidak Aktif</strong> secara default. Anda harus mengaktifkannya secara manual dari halaman utama untuk menggantikan model yang sedang berjalan di produksi.
                    </p>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-3 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.model-svm.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary px-8">Register Model</button>
            </div>
        </form>
    </div>
</div>

@endsection
