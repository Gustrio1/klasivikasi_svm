@extends('layouts.app')

@section('title', 'Tambah Media Hafalan (Admin)')

@section('content')

<x-page-header
    title="Tambah Media Hafalan"
    subtitle="Media hafalan akan digunakan sebagai sumber referensi rekomendasi berdasarkan hasil klasifikasi SVM."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Media Hafalan', 'url' => route('admin.media-hafalan.index')],
        ['label' => 'Tambah Media', 'url' => null],
    ]"
/>

<div class="max-w-5xl mx-auto pb-12" x-data="{ jenis_media: '{{ old('jenis_media', '') }}' }">
    <div class="card p-8 border-none shadow-2xl">
        <form action="{{ route('admin.media-hafalan.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan Input</h3>
                            <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-10 max-w-2xl mx-auto">
                {{-- Kolom Kiri: Informasi Utama --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest border-b border-gray-100 pb-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        Informasi Utama
                    </h3>

                    <div>
                        <label class="form-label">Nama Media <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_media" value="{{ old('nama_media') }}" 
                            class="form-input @error('nama_media') border-red-500 @enderror" 
                            required placeholder="Contoh: Aplikasi Hafalan Cerdas">
                        @error('nama_media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="form-label">Jenis Media <span class="text-red-500">*</span></label>
                            <select name="jenis_media" x-model="jenis_media" class="form-input @error('jenis_media') border-red-500 @enderror" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="cetak">Media Cetak</option>
                                <option value="digital">Media Digital (Aplikasi)</option>
                            </select>
                            @error('jenis_media') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- URL Link (Hanya Tampil Jika Digital) --}}
                    <div x-show="jenis_media === 'digital'" style="display: none;" x-transition>
                        <label class="form-label text-indigo-700">URL Link Aplikasi <span class="text-red-500">*</span></label>
                        <input type="url" name="url_link" value="{{ old('url_link') }}" 
                            class="form-input @error('url_link') border-red-500 @enderror" 
                            placeholder="Contoh: https://play.google.com/...">
                        <p class="text-[10px] text-gray-400 mt-1">Wajib diisi untuk media digital (tautan unduhan atau akses aplikasi).</p>
                        @error('url_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <div class="mt-12 flex justify-end gap-3 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.media-hafalan.index') }}" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary px-8">Simpan Media Baru</button>
            </div>
        </form>
    </div>
</div>

@endsection
