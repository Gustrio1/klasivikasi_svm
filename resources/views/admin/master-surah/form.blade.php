@extends('layouts.app')

@section('title', $surah ? 'Edit Surat' : 'Tambah Surat')

@section('content')

<x-page-header
    :title="$surah ? 'Edit Surat: ' . $surah->nama_surah : 'Tambah Surat Baru'"
    subtitle="Data surat akan muncul sebagai pilihan pada form input hafalan guru"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Surat', 'url' => route('admin.master-surah.index')],
        ['label' => $surah ? 'Edit' : 'Tambah', 'url' => null],
    ]"
>
    <a href="{{ route('admin.master-surah.index') }}" class="btn-secondary">← Kembali</a>
</x-page-header>

<div class="max-w-lg">
    <div class="card p-6">
        <form method="POST"
              action="{{ $surah ? route('admin.master-surah.update', $surah) : route('admin.master-surah.store') }}">
            @csrf
            @if($surah) @method('PUT') @endif

            <div class="space-y-5">
                {{-- Nomor Surat --}}
                <div>
                    <label for="nomor_surah" class="form-label">Nomor Surat <span class="text-red-500">*</span></label>
                    <input type="number" name="nomor_surah" id="nomor_surah"
                           class="form-input @error('nomor_surah') border-red-500 @enderror"
                           value="{{ old('nomor_surah', $surah?->nomor_surah) }}"
                           min="1" max="114" required placeholder="Contoh: 78">
                    <p class="text-xs text-gray-400 mt-1">Nomor urut surat dalam Al-Qur'an (1–114)</p>
                    @error('nomor_surah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Surat --}}
                <div>
                    <label for="nama_surah" class="form-label">Nama Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_surah" id="nama_surah"
                           class="form-input @error('nama_surah') border-red-500 @enderror"
                           value="{{ old('nama_surah', $surah?->nama_surah) }}"
                           required placeholder="Contoh: An-Naba'">
                    @error('nama_surah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah Ayat --}}
                <div>
                    <label for="jumlah_ayat" class="form-label">Jumlah Ayat <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_ayat" id="jumlah_ayat"
                           class="form-input @error('jumlah_ayat') border-red-500 @enderror"
                           value="{{ old('jumlah_ayat', $surah?->jumlah_ayat) }}"
                           min="1" max="1000" required placeholder="Contoh: 40">
                    <p class="text-xs text-gray-400 mt-1">Total ayat resmi dalam surat ini (info di dropdown guru)</p>
                    @error('jumlah_ayat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="btn-primary">
                    {{ $surah ? 'Simpan Perubahan' : 'Tambah Surat' }}
                </button>
                <a href="{{ route('admin.master-surah.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
