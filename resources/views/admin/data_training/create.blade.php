@extends('layouts.app')

@section('title', 'Tambah Data Training (Admin)')

@section('content')

<x-page-header
    title="Tambah Sampel Dataset SVM"
    subtitle="Dataset digunakan untuk melatih model klasifikasi berdasarkan fitur: Jumlah Ayat, Usia Siswa, dan Media Pembelajaran."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Data Training', 'url' => route('admin.data-training.index')],
        ['label' => 'Tambah Data', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card p-8 border-none shadow-2xl">
        
        {{-- Info Banner --}}
        <div class="mb-8 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-start gap-3">
            <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-indigo-700 font-medium">
                Model SVM menggunakan <strong>3 fitur</strong>: <strong>Total Surat</strong> yang dihafal dalam satu semester, Usia siswa (dalam tahun), dan Media Hafalan yang digunakan. Kombinasi ketiga fitur ini menentukan label klasifikasi akhir: <strong>Lulus (≥30 surat) / Tidak Lulus (&lt;30 surat)</strong>.
            </p>
        </div>

        <form action="{{ route('admin.data-training.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Fitur SVM --}}
                <div class="space-y-6">
                    <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        Fitur Input SVM
                    </h4>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Surat Hafalan / Semester</label>
                        <input type="number" name="fitur_total_surah" value="{{ old('fitur_total_surah') }}" 
                            class="input-base" required min="1" max="120" placeholder="Contoh: 30">
                        <p class="text-[10px] text-gray-400">Total surat unik yang berhasil dihafal siswa dalam satu semester. Batas lulus: ≥30 surat.</p>
                        @error('fitur_total_surah') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Usia Siswa (Tahun)</label>
                        <input type="number" name="fitur_usia" value="{{ old('fitur_usia', 12) }}" 
                            class="input-base" required min="5" max="30" placeholder="Contoh: 13">
                        <p class="text-[10px] text-gray-400">Usia siswa dalam satuan tahun saat data direkam.</p>
                        @error('fitur_usia') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Media Hafalan yang Digunakan</label>
                        <select name="id_media" class="input-base" required>
                            <option value="">-- Pilih Media --</option>
                            @foreach($medias as $media)
                                <option value="{{ $media->id }}" {{ old('id_media') == $media->id ? 'selected' : '' }}>
                                    {{ $media->nama_media }} ({{ ucfirst($media->jenis_media) }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400">Media pembelajaran yang digunakan siswa dalam proses menghafal.</p>
                        @error('id_media') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Label & Metadata --}}
                <div class="space-y-6">
                    <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                        <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                        Label & Metadata
                    </h4>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Label Kelas (Ground Truth)</label>
                        <select name="label_kelas" class="input-base" required>
                            <option value="Lulus" {{ old('label_kelas') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="Tidak Lulus" {{ old('label_kelas') == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                        </select>
                        <p class="text-[10px] text-gray-400">Label yang seharusnya untuk kombinasi fitur di samping (diisi oleh pakar/guru).</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Sumber Data</label>
                        <input type="text" name="sumber_data" value="{{ old('sumber_data', 'Manual Input') }}" 
                            class="input-base" placeholder="Contoh: Tes Internal 2024">
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_valid" value="1" id="is_valid" checked 
                                class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            <div>
                                <label for="is_valid" class="text-xs font-black text-gray-700 uppercase tracking-tighter">Data Sudah Tervalidasi</label>
                                <p class="text-[10px] text-gray-400 mt-0.5">Centang jika data ini siap digunakan untuk proses training model.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-3 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.data-training.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary px-8">Simpan Sampel</button>
            </div>
        </form>
    </div>
</div>

@endsection
