@extends('layouts.app')

@section('title', 'Katalog Media Belajar')

@section('content')

<x-page-header
    title="Katalog Media Belajar"
    subtitle="Jelajahi berbagai materi dan aplikasi bantu untuk meningkatkan kualitas hafalan"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Media Belajar', 'url' => null],
    ]"
/>

{{-- Filter Box --}}
<div class="card mb-6 bg-white border border-gray-100 shadow-sm rounded-xl p-5 block">
    <form method="GET" action="{{ route('siswa.media-hafalan.index') }}" class="flex flex-col sm:flex-row gap-4 items-center">
        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Media:</label>
        
        <div class="flex-1 w-full sm:w-auto">
            <select name="jenis_media" class="form-input w-full">
                <option value="">Semua Jenis Form</option>
                <option value="digital" {{ request('jenis_media') === 'digital' ? 'selected' : '' }}>Digital</option>
                <option value="cetak" {{ request('jenis_media') === 'cetak' ? 'selected' : '' }}>Cetak / Buku</option>
            </select>
        </div>
        
        <div class="flex-1 w-full sm:w-auto">
            <select name="kelas_target" class="form-input w-full">
                <option value="">Semua Kelas</option>
                <option value="A" {{ request('kelas_target') === 'A' ? 'selected' : '' }}>Kelas A (Sangat Baik)</option>
                <option value="B" {{ request('kelas_target') === 'B' ? 'selected' : '' }}>Kelas B (Cukup)</option>
                <option value="C" {{ request('kelas_target') === 'C' ? 'selected' : '' }}>Kelas C (Perlu Bimbingan)</option>
            </select>
        </div>
        
        <button type="submit" class="btn-primary w-full sm:w-auto px-6">Terapkan</button>
        
        @if(request('jenis_media') || request('kelas_target'))
            <a href="{{ route('siswa.media-hafalan.index') }}" class="btn-secondary w-full sm:w-auto px-4 text-center">Reset</a>
        @endif
    </form>
</div>

{{-- Grid Media --}}
@if($medias->isEmpty())
    <div class="card py-16 text-center">
        <p class="text-5xl mb-4">📚</p>
        <p class="text-gray-500 font-medium text-lg">Belum ada media yang tersedia</p>
        <p class="text-gray-400 text-sm mt-1">Ganti filter Anda atau hubungi guru Anda</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($medias as $media)
            <div class="flex flex-col bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition duration-300 overflow-hidden group">
                {{-- Header Card --}}
                <div class="p-5 border-b border-gray-50 bg-gray-50/50 group-hover:bg-teal-50/30 transition">
                    <div class="flex justify-between items-start mb-2">
                        <span class="badge-{{ $media->kelas_target }} text-xs font-semibold px-2 py-0.5 rounded-full shadow-sm">
                            Kelas {{ $media->kelas_target }}
                        </span>
                        @if($media->jenis_media === 'digital')
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 bg-blue-100 px-2.5 py-1 rounded-full">
                                📱 Digital
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">
                                📖 Cetak
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 leading-tight mb-1">{{ $media->nama_media }}</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                        {{ $media->format_file ?? 'Media Fisik / Panduan' }}
                    </p>
                </div>

                {{-- Body Card --}}
                <div class="p-5 flex-1 flex flex-col">
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                        {{ $media->deskripsi ?? 'Tidak ada deskripsi rinci untuk media ini.' }}
                    </p>
                    
                    @if($media->tips_belajar)
                        <div class="mt-auto bg-yellow-50 text-yellow-800 p-3 rounded-lg text-xs border border-yellow-100 mb-4">
                            <p class="font-bold flex items-center gap-1 mb-1">
                                💡 Tips Singkat
                            </p>
                            <p class="line-clamp-2 opacity-90 leading-relaxed">{{ $media->tips_belajar }}</p>
                        </div>
                    @endif
                    
                    @if($media->jenis_media === 'digital' && $media->url_link)
                        <div class="mt-auto">
                            <a href="{{ $media->url_link }}" target="_blank"
                               class="flex justify-center items-center w-full py-2.5 px-4 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg text-sm transition shadow-sm hover:shadow">
                                Buka Materi ↗
                            </a>
                        </div>
                    @else
                        <div class="mt-auto bg-gray-100 text-gray-500 py-2.5 px-4 rounded-lg text-center text-sm font-medium border border-gray-200 border-dashed">
                            Tersedia Offline
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($medias->hasPages())
        <div class="mt-8 pt-4 border-t border-gray-100">
            {{ $medias->withQueryString()->links() }}
        </div>
    @endif
@endif

@endsection
