{{-- =========================================================
     Page Header — resources/views/components/page-header.blade.php
     Props: $title, $subtitle (opsional), $links (breadcrumb array)
     $slot: area untuk tombol aksi
     ========================================================= --}}

@props([
    'title'    => '',
    'subtitle' => '',
    'links'    => [],
])

<div class="mb-6">

    {{-- Breadcrumb --}}
    @if(count($links) > 0)
        <div class="mb-3">
            <x-breadcrumb :links="$links" />
        </div>
    @endif

    {{-- Title + Subtitle + Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

        {{-- Kiri: Judul & Subjudul --}}
        <div>
            <h1 class="text-xl font-bold text-gray-900 leading-tight">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Kanan: Slot tombol aksi --}}
        @if($slot->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                {{ $slot }}
            </div>
        @endif

    </div>

    {{-- Divider --}}
    <div class="mt-4 border-b border-gray-200"></div>

</div>
