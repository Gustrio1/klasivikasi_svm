@extends('layouts.app')

@section('title', 'Katalog Media Hafalan')

@section('content')

<x-page-header
    title="Pustaka Media Rekomendasi"
    subtitle="Direktori bahan ajar dan alat bantu hafalan berdasarkan target kelas kompetensi"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Media Hafalan', 'url' => null],
    ]"
/>

<div class="card p-0 overflow-hidden">
    
    <div class="p-6 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="flex items-center gap-4">
            <h3 class="text-lg font-bold text-gray-800">Daftar Tersedia</h3>
            <a href="{{ route('admin.media-hafalan.create') }}" class="btn-primary py-2 px-4 shadow-sm text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Media
            </a>
        </div>
        
        <form method="GET" action="" class="flex  gap-2 w-full sm:w-auto">

            
            <select name="jenis_media" class="form-input py-2 text-sm max-w-[150px]">
                <option value="">Semua Jenis</option>
                <option value="digital" {{ request('jenis_media') == 'digital' ? 'selected' : '' }}>Digital (URL)</option>
                <option value="cetak" {{ request('jenis_media') == 'cetak' ? 'selected' : '' }}>Cetak / Fisik</option>
            </select>
            
            <button type="submit" class="btn-secondary py-2 px-4 shadow-sm text-sm">Filter</button>
            @if(request('jenis_media'))
                <a href="{{ url()->current() }}" class="btn-secondary bg-gray-200 text-gray-600 hover:bg-gray-300 py-2 px-3 text-sm flex items-center justify-center" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($medias as $media)
            <div class="border {{ $media->jenis_media == 'digital' ? 'border-indigo-100 bg-indigo-50/30' : 'border-amber-100 bg-amber-50/30' }} rounded-xl p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
                
                {{-- Decorative element removed --}}

                <div class="flex items-start justify-between relative z-10">
                    <div class="bg-white p-2 rounded-lg shadow-sm {{ $media->jenis_media == 'digital' ? 'text-indigo-600' : 'text-amber-600' }}">
                        @if($media->jenis_media == 'digital')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @endif
                    </div>
                </div>
                
                <a href="{{ route('admin.media-hafalan.show', $media->id) }}" class="mt-4 inline-block relative z-10 group/title">
                    <h4 class="text-lg font-bold text-gray-800 leading-tight group-hover/title:text-indigo-600 transition-colors">
                        {{ $media->nama_media }}
                    </h4>
                </a>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2 relative z-10">{{ $media->keterangan ?? 'Tidak ada deskripsi rinci untuk media bahan ajar ini.' }}</p>
                
                <div class="mt-5 pt-4 border-t border-black/5 relative z-10 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">
                        {{ $media->jenis_media == 'digital' ? 'Materi Digital' : 'Alat Peraga / Buku' }}
                    </span>
                    
                    <div class="flex items-center gap-2">
                        @if($media->jenis_media == 'digital' && $media->url_link)
                            <a href="{{ $media->url_link }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 px-3 py-1.5 bg-indigo-100/50 hover:bg-indigo-100 rounded-lg transition-colors" title="Kunjungi Tautan">
                                <span class="hidden sm:inline">Tautan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <span class="text-xs text-amber-600 bg-amber-100/50 px-3 py-1.5 rounded-lg border border-amber-100">Benda Fisik</span>
                        @endif

                        <form action="{{ route('admin.media-hafalan.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus media ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center p-1.5 text-rose-500 hover:text-white hover:bg-rose-500 rounded-lg transition-colors border border-transparent hover:border-rose-600 bg-rose-50/50" title="Hapus Media">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-12 text-center bg-white rounded-xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Media Kosong</h3>
                <p class="text-gray-500 max-w-sm mx-auto mt-2">Belum ada satupun koleksi media hafalan yang didaftarkan ke dalam pangkalan data pada filter tersebut.</p>
            </div>
        @endforelse
    </div>

    @if($medias->hasPages())
        <div class="p-6 border-t border-gray-100 bg-gray-50/30">
            {{ $medias->links() }}
        </div>
    @endif
    
</div>

@endsection
