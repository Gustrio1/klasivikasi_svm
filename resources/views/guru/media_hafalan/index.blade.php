@extends('layouts.app')

@section('title', 'Media Hafalan (Guru)')

@section('content')

<x-page-header
    title="Pusat Sumber Belajar"
    subtitle="Lihat dan kelola media hafalan sebagai referensi bimbingan siswa."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Media Hafalan', 'url' => null],
    ]"
>
     <a href="{{ route('guru.media-hafalan.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Media
    </a>
</x-page-header>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">
    @forelse($medias as $m)
        <div class="card group hover:shadow-2xl transition-all duration-300 border-none relative overflow-hidden bg-white">
            {{-- Icon/Type Indicator --}}
            <div class="absolute top-0 right-0 p-4">
                @if($m->jenis_media === 'video')
                    <span class="w-10 h-10 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center border border-rose-100 group-hover:bg-rose-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                @elseif($m->jenis_media === 'audio')
                    <span class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center border border-blue-100 group-hover:bg-blue-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                    </span>
                @else
                    <span class="w-10 h-10 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center border border-teal-100 group-hover:bg-teal-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </span>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-6">
                <div class="mb-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b-2 border-teal-400 pb-1">TARGET: KELAS {{ $m->kelas_target }}</span>
                    <h3 class="mt-3 text-lg font-black text-gray-800 leading-tight group-hover:text-teal-600 transition">{{ $m->nama_media }}</h3>
                    <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $m->deskripsi }}</p>
                </div>

                <div class="flex items-center justify-between mt-8">
                    @if($m->url_link)
                        <a href="{{ $m->url_link }}" target="_blank" class="flex items-center gap-2 text-xs font-bold text-teal-600 hover:text-teal-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka Materi
                        </a>
                    @else
                         <span class="text-xs font-bold text-gray-400 italic">Materi Cetak</span>
                    @endif

                    <div class="flex gap-2">
                         <a href="{{ route('guru.media-hafalan.edit', $m->id) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('guru.media-hafalan.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan media ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Non-aktifkan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <p class="text-gray-400 italic">Belum ada media hafalan yang tersedia.</p>
        </div>
    @endforelse
</div>

@if($medias->hasPages())
    <div class="mt-8">
        {{ $medias->links() }}
    </div>
@endif

@endsection
