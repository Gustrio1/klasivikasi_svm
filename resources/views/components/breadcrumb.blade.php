{{-- =========================================================
     Breadcrumb — resources/views/components/breadcrumb.blade.php
     Props: $links = [['label' => '...', 'url' => '...'|null]]
     Item terakhir (url=null) tampil sebagai current page
     ========================================================= --}}

@props(['links' => []])

@if(count($links) > 0)
<nav aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm">

        {{-- Home icon di awal --}}
        <li>
            <a href="{{ route('dashboard') }}"
               class="text-gray-400 hover:text-teal-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
        </li>

        @foreach($links as $index => $link)
            {{-- Separator --}}
            <li class="text-gray-300 select-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>

            <li>
                @if(!empty($link['url']) && $index < count($links) - 1)
                    {{-- Link aktif --}}
                    <a href="{{ $link['url'] }}"
                       class="text-gray-500 hover:text-teal-600 transition font-medium hover:underline decoration-teal-400 underline-offset-2">
                        {{ $link['label'] }}
                    </a>
                @else
                    {{-- Current page (item terakhir) --}}
                    <span class="text-teal-700 font-semibold" aria-current="page">
                        {{ $link['label'] }}
                    </span>
                @endif
            </li>
        @endforeach

    </ol>
</nav>
@endif
