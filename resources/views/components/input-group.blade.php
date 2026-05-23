@props([
    'label' => '',
    'icon'  => null,
    'error' => null,
])

<div class="space-y-1.5">
    @if($label)
        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">{{ $label }}</label>
    @endif
    
    <div class="relative group">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-teal-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                </svg>
            </div>
        @endif
        
        <div class="{{ $icon ? 'pl-11' : '' }}">
            {{ $slot }}
        </div>
    </div>
    
    @if($error)
        <p class="text-[10px] font-bold text-rose-500 ml-1 mt-1 uppercase tracking-tighter">{{ $error }}</p>
    @endif
</div>
