{{-- =========================================================
     Alert — resources/views/components/alert.blade.php
     Auto-detect flash session: success, error, warning, info
     Auto-dismiss setelah 5 detik
     ========================================================= --}}

@php
    // Tentukan type dan pesan dari session
    $alertType = null;
    $alertMessage = null;

    foreach (['success', 'error', 'warning', 'info'] as $type) {
        if (session()->has($type)) {
            $alertType    = $type;
            $alertMessage = session($type);
            break;
        }
    }

    $configs = [
        'success' => [
            'bg'     => 'bg-green-50 border-green-300',
            'icon_bg' => 'bg-green-100',
            'text'   => 'text-green-800',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
            'icon_color' => 'text-green-500',
            'label'  => 'Berhasil',
        ],
        'error' => [
            'bg'     => 'bg-red-50 border-red-300',
            'icon_bg' => 'bg-red-100',
            'text'   => 'text-red-800',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
            'icon_color' => 'text-red-500',
            'label'  => 'Error',
        ],
        'warning' => [
            'bg'     => 'bg-yellow-50 border-yellow-300',
            'icon_bg' => 'bg-yellow-100',
            'text'   => 'text-yellow-800',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
            'icon_color' => 'text-yellow-500',
            'label'  => 'Perhatian',
        ],
        'info' => [
            'bg'     => 'bg-blue-50 border-blue-300',
            'icon_bg' => 'bg-blue-100',
            'text'   => 'text-blue-800',
            'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'icon_color' => 'text-blue-500',
            'label'  => 'Informasi',
        ],
    ];
@endphp

@if($alertType && $alertMessage)
    @php $cfg = $configs[$alertType]; @endphp

    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mb-5 flex items-start gap-3 rounded-xl border p-4 {{ $cfg['bg'] }}"
    >
        {{-- Icon --}}
        <div class="flex-shrink-0 {{ $cfg['icon_bg'] }} rounded-full p-1.5">
            <svg class="w-4 h-4 {{ $cfg['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $cfg['icon'] !!}
            </svg>
        </div>

        {{-- Message --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold {{ $cfg['text'] }}">{{ $cfg['label'] }}</p>
            <p class="text-sm {{ $cfg['text'] }} opacity-90 mt-0.5">{{ $alertMessage }}</p>
        </div>

        {{-- Close Button --}}
        <button @click="show = false"
                class="flex-shrink-0 {{ $cfg['text'] }} opacity-60 hover:opacity-100 transition ml-2 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

    </div>
@endif

{{-- Juga tampilkan @errors validasi global jika ada --}}
@if($errors->any())
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4"
    >
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 bg-red-100 rounded-full p-1.5">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan validasi:</p>
                <ul class="mt-1 list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif
