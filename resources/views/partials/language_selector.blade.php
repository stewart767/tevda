@php
    $current = app()->getLocale();
    $locales = [
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
            'short' => 'EN',
            'gt' => '/en/en',
        ],
        'sw' => [
            'code' => 'sw',
            'name' => 'Kiswahili',
            'native' => 'Kiswahili',
            'flag' => '🇹🇿',
            'short' => 'SW',
            'gt' => '/en/sw',
        ],
        'zh' => [
            'code' => 'zh',
            'name' => 'Chinese',
            'native' => '中文 (Simplified)',
            'flag' => '🇨🇳',
            'short' => 'ZH',
            'gt' => '/en/zh-CN',
        ],
    ];
    $active = $locales[$current] ?? $locales['sw'];
@endphp

<!-- Desktop Language Switcher Dropdown -->
<div class="relative inline-block text-left notranslate" x-data="{ langDropdownOpen: false }" @click.away="langDropdownOpen = false">
    <button type="button" 
            @click="langDropdownOpen = !langDropdownOpen"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 hover:text-emerald-700 shadow-xs focus:outline-hidden hover:border-emerald-300"
            aria-expanded="langDropdownOpen"
            aria-haspopup="true"
            title="{{ __('Select Language') }}">
        <span class="text-base leading-none">{{ $active['flag'] }}</span>
        <span class="font-extrabold tracking-wide text-[11px] sm:text-xs text-slate-800">{{ $active['short'] }}</span>
        <span class="hidden xl:inline-block text-[11px] text-slate-500 font-medium">({{ $active['name'] }})</span>
        <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': langDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="langDropdownOpen" 
         x-transition:enter="transition ease-out duration-150" 
         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
         x-transition:leave="transition ease-in duration-100" 
         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
         class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-2xl border border-slate-100 p-1.5 z-50 ring-1 ring-black/5 backdrop-blur-xl divide-y divide-slate-50"
         style="display: none;">
        
        <div class="px-3 py-2">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span>{{ __('Select Language') }}</span>
            </span>
        </div>

        <div class="p-1 space-y-1">
            @foreach($locales as $code => $item)
                @php $isSelected = ($current === $code); @endphp
                <a href="{{ route('locale.switch', $code) }}" 
                   onclick="window.tevdaSetLanguage && window.tevdaSetLanguage('{{ $code }}')"
                   class="flex items-center justify-between w-full px-3 py-2 rounded-xl text-xs transition-all duration-150 group {{ $isSelected ? 'bg-emerald-50 text-emerald-950 font-extrabold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900 font-semibold' }}">
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg leading-none">{{ $item['flag'] }}</span>
                        <div class="text-left">
                            <span class="block text-xs leading-snug {{ $isSelected ? 'text-emerald-900' : 'text-slate-800' }}">{{ $item['native'] }}</span>
                            <span class="block text-[10px] {{ $isSelected ? 'text-emerald-600' : 'text-slate-400' }}">{{ $item['name'] }} ({{ $item['short'] }})</span>
                        </div>
                    </div>
                    @if($isSelected)
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-600 text-white shadow-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
