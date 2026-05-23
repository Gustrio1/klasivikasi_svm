@extends('layouts.app')

@section('title', 'Detail Media Hafalan (Admin)')

@section('content')

<x-page-header
    title="Detail Media Hafalan"
    subtitle="Lihat informasi lengkap beserta tips dan alasan rekomendasi."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Media Hafalan', 'url' => route('admin.media-hafalan.index')],
        ['label' => 'Detail Media', 'url' => null],
    ]"
/>

<div class="max-w-5xl mx-auto pb-12">
    <div class="card p-8 border-none shadow-2xl">
        <div class="grid grid-cols-1 gap-8 max-w-2xl mx-auto">
            {{-- Kolom Kiri: Header & Meta --}}
            <div class="space-y-6">
                <div class="p-6 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl text-white shadow-lg shadow-indigo-200">

                    <h2 class="text-2xl font-black mb-4">{{ $mediaHafalan->nama_media }}</h2>
                    
                    <div class="flex flex-wrap gap-2">
                        @if($mediaHafalan->jenis_media === 'digital')
                            <span class="px-2.5 py-1 bg-white/20 rounded-lg text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                                Aplikasi Digital
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-white/20 rounded-lg text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                                Buku / Cetak
                            </span>
                        @endif

                        @if($mediaHafalan->is_active)
                            <span class="px-2.5 py-1 bg-teal-500 rounded-lg text-xs font-bold uppercase tracking-wider">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 bg-rose-500 rounded-lg text-xs font-bold uppercase tracking-wider">Nonaktif</span>
                        @endif
                    </div>
                </div>

                @if($mediaHafalan->jenis_media === 'digital' && $mediaHafalan->url_link)
                    <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tautan Aplikasi</p>
                        <a href="{{ $mediaHafalan->url_link }}" target="_blank" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 break-all">
                            {{ $mediaHafalan->url_link }}
                        </a>
                    </div>
                @endif
                
                <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ditambahkan Pada</p>
                    <p class="text-sm font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($mediaHafalan->tanggal_input)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
        </div>

        <div class="mt-12 flex justify-end gap-3 border-t border-gray-100 pt-8">
            <a href="{{ route('admin.media-hafalan.index') }}" class="btn-secondary">Kembali</a>
            
            <form action="{{ route('admin.media-hafalan.destroy', $mediaHafalan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus media ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-3 bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-200 font-black px-8 hover:bg-rose-600 transition">Hapus Data</button>
            </form>

            <a href="{{ route('admin.media-hafalan.edit', $mediaHafalan->id) }}" class="p-3 bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-200 font-black px-8 hover:bg-indigo-600 transition">Edit Data</a>
        </div>
    </div>
</div>

@endsection
