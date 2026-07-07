@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile View --}}
        <div class="flex flex-1 justify-between sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed select-none shadow-sm">
                    {!! __('&laquo; Sebelumnya') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-600 transition shadow-sm active:bg-gray-100">
                    {!! __('&laquo; Sebelumnya') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-600 transition shadow-sm active:bg-gray-100">
                    {!! __('Berikutnya &raquo;') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed select-none shadow-sm">
                    {!! __('Berikutnya &raquo;') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center p-2 text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed shadow-sm" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center p-2 text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-600 transition shadow-sm" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="relative inline-flex items-center px-3 py-1.5 text-gray-400 text-xs font-medium select-none">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="relative inline-flex items-center px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-lg shadow-sm border border-teal-600 z-10" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1.5 bg-white text-gray-700 border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-600 hover:border-teal-300 transition text-xs font-semibold shadow-sm" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center p-2 text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-600 transition shadow-sm" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center p-2 text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed shadow-sm" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
