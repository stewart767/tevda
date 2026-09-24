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
            'native' => '中文 (简体)',
            'flag' => '🇨🇳',
            'short' => 'ZH',
            'gt' => '/en/zh-CN',
        ],
    ];
@endphp

<!-- Mobile Language Switcher -->
<div class="border-t border-slate-100 pt-3 notranslate">
    <div class="flex items-center justify-between px-3.5 mb-2">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            <span>{{ __('Language') }} / Lugha / 语言</span>
        </span>
    </div>
    <div class="grid grid-cols-3 gap-1.5 px-1">
        @foreach($locales as $code => $item)
            @php $isSelected = ($current === $code); @endphp
            <a href="{{ route('locale.switch', $code) }}" 
               onclick="window.tevdaSetLanguage && window.tevdaSetLanguage('{{ $code }}')"
               class="flex flex-col items-center justify-center p-2 rounded-xl border text-center transition-all duration-150 {{ $isSelected ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm font-extrabold' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80 font-medium' }}">
                <span class="text-xl mb-0.5">{{ $item['flag'] }}</span>
                <span class="text-xs {{ $isSelected ? 'text-white' : 'text-slate-800' }}">{{ $item['native'] }}</span>
                <span class="text-[9px] uppercase tracking-wide {{ $isSelected ? 'text-emerald-100' : 'text-slate-400' }}">{{ $item['short'] }}</span>
            </a>
        @endforeach
    </div>
</div>
