@extends('layouts.app')

@section('title', 'Master Data Surat')

@section('content')

<x-page-header
    title="Master Data Surat Al-Qur'an"
    subtitle="Kelola daftar nama surat dan jumlah ayat yang digunakan pada form input hafalan"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Surat', 'url' => null],
    ]"
>
    <a href="{{ route('admin.master-surah.create') }}" class="btn-primary">
        + Tambah Surat
    </a>
</x-page-header>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="card overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <div>
            <p class="text-sm font-semibold text-gray-800">Total: <span class="text-teal-600 font-bold">{{ $surahs->count() }} surat</span></p>
            <p class="text-xs text-gray-400 mt-0.5">Aktif: {{ $surahs->where('is_active', true)->count() }} | Nonaktif: {{ $surahs->where('is_active', false)->count() }}</p>
        </div>
        <span class="text-xs text-gray-400 italic">Juz 30 (Juz Amma)</span>
    </div>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="table-th text-center w-16">No. Surat</th>
                    <th class="table-th">Nama Surat</th>
                    <th class="table-th text-center">Jumlah Ayat</th>
                    <th class="table-th text-center">Status</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surahs as $surah)
                    <tr class="{{ !$surah->is_active ? 'opacity-50' : '' }}">
                        <td class="table-td text-center">
                            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $surah->nomor_surah }}</span>
                        </td>
                        <td class="table-td">
                            <span class="font-bold text-gray-800">{{ $surah->nama_surah }}</span>
                        </td>
                        <td class="table-td text-center">
                            <span class="text-sm font-bold text-teal-700">{{ $surah->jumlah_ayat }}</span>
                            <span class="text-xs text-gray-400">ayat</span>
                        </td>
                        <td class="table-td text-center">
                            @if($surah->is_active)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                                    ● Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    ● Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="table-td text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.master-surah.edit', $surah) }}" class="text-xs font-semibold text-indigo-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.master-surah.toggle', $surah) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold {{ $surah->is_active ? 'text-red-500 hover:underline' : 'text-emerald-600 hover:underline' }}">
                                        {{ $surah->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-td text-center text-gray-400 italic py-8">Belum ada data surat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
