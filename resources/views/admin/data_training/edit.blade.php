@extends('layouts.app')

@section('title', 'Edit Data Training (Admin)')

@section('content')

<x-page-header
    title="Koreksi Sampel Dataset"
    subtitle="Perbarui nilai fitur atau label jika terdapat kesalahan pada data training SVM."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Data Training', 'url' => route('admin.data-training.index')],
        ['label' => 'Edit Data', 'url' => null],
    ]"
/>

<div class="max-w-4xl mx-auto pb-12">
    <div class="card p-8 border-none shadow-2xl">
        <form action="{{ route('admin.data-training.update', $dataTraining->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Fitur SVM --}}
                <div class="space-y-6">
                    <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        Fitur Input SVM
                    </h4>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Surat Hafalan / Semester</label>
                        <input type="number" name="fitur_total_surah" value="{{ old('fitur_total_surah', $dataTraining->fitur_total_surah) }}" 
                            class="input-base" required min="1" max="120">
                        <p class="text-[10px] text-gray-400">Total surat unik yang dihafal selama satu semester. Batas lulus: &ge;30 surat.</p>
                        @error('fitur_total_surah') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Usia Siswa (Tahun)</label>
                        <input type="number" name="fitur_usia" 
                            value="{{ old('fitur_usia', $dataTraining->fitur_usia) }}" 
                            class="input-base" required min="5" max="30">
                        @error('fitur_usia') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Media Hafalan</label>
                        <select name="id_media" class="input-base" required>
                            <option value="">-- Pilih Media --</option>
                            @foreach($medias as $media)
                                <option value="{{ $media->id }}" {{ old('id_media', $dataTraining->id_media) == $media->id ? 'selected' : '' }}>
                                    {{ $media->nama_media }} ({{ ucfirst($media->jenis_media) }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_media') <p class="text-[10px] font-bold text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Label & Metadata --}}
                <div class="space-y-6">
                    <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        Label & Metadata
                    </h4>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Label Kelas</label>
                        <select name="label_kelas" class="input-base" required>
                            <option value="Lulus" {{ old('label_kelas', $dataTraining->label_kelas) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="Tidak Lulus" {{ old('label_kelas', $dataTraining->label_kelas) == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Sumber Data</label>
                        <input type="text" name="sumber_data" value="{{ old('sumber_data', $dataTraining->sumber_data) }}" class="input-base">
                    </div>

                    <div class="mt-4 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <input type="checkbox" name="is_valid" value="1" id="is_valid" 
                            {{ $dataTraining->is_valid ? 'checked' : '' }} 
                            class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <label for="is_valid" class="text-xs font-black text-gray-700 uppercase tracking-tighter">Validasi Data</label>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-3 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.data-training.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="p-3 bg-amber-500 text-white rounded-xl shadow-lg shadow-amber-200 font-black px-8 hover:bg-amber-600 transition">Update Data</button>
            </div>
        </form>
    </div>
</div>

@endsection
