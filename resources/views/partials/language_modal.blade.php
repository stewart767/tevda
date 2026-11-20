@php
    $currentLocale = app()->getLocale();
    $swUrl = route('locale.switch', ['locale' => 'sw', 'return_url' => request()->fullUrl()]);
    $enUrl = route('locale.switch', ['locale' => 'en', 'return_url' => request()->fullUrl()]);
    $zhUrl = route('locale.switch', ['locale' => 'zh', 'return_url' => request()->fullUrl()]);
@endphp

<!-- TEVDA Initial Language Selection Popout Modal -->
<div id="tevdaLanguageModal" 
     class="fixed inset-0 z-[999999] notranslate flex items-center justify-center p-4 sm:p-6 md:p-8 transition-opacity duration-300 opacity-0 pointer-events-none"
     role="dialog"
     aria-modal="true"
     aria-labelledby="tevda-language-modal-title">

    <!-- Dark Blurred Backdrop -->
    <div id="tevdaLanguageModalBackdrop" 
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity duration-300 cursor-pointer"
         onclick="window.tevdaCloseLanguageModal()">
    </div>

    <!-- Centered Modal Content Card (Matches user reference design) -->
    <div id="tevdaLanguageModalCard" 
         class="relative w-full max-w-2xl sm:max-w-3xl bg-white rounded-3xl sm:rounded-[32px] shadow-2xl border border-slate-100 p-6 sm:p-10 md:p-12 text-center transform scale-95 transition-all duration-300 z-10">

        <!-- Top-Right Close Button -->
        <button type="button" 
                onclick="window.tevdaCloseLanguageModal()"
                class="absolute top-4 right-4 sm:top-6 sm:right-6 p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors cursor-pointer"
                title="Funga / Close / 关闭">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Top Circular Icon Badge with Green Checkmark (Exactly like reference) -->
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-xs">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Main Title (Clean & Minimal) -->
        <h2 id="tevda-language-modal-title" class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight font-heading mb-6 sm:mb-8">
            Chagua Lugha / Choose Language
        </h2>

        <!-- Language Action Buttons Row (Horizontal side-by-side) -->
        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 mb-2">
            <!-- 1. Kiswahili Button (Emerald Green) -->
            <button type="button" 
                    onclick="window.tevdaSelectLanguage('sw', '{{ $swUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-md shadow-emerald-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>Kiswahili</span>
            </button>

            <!-- 2. English Button (Royal Blue) -->
            <button type="button" 
                    onclick="window.tevdaSelectLanguage('en', '{{ $enUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-blue-600 hover:bg-blue-700 active:scale-95 shadow-md shadow-blue-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>English</span>
            </button>

            <!-- 3. Chinese Button (Vibrant Coral / Red) -->
            <button type="button" 
                    onclick="window.tevdaSelectLanguage('zh', '{{ $zhUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-rose-600 hover:bg-rose-700 active:scale-95 shadow-md shadow-rose-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>中文 (Chinese)</span>
            </button>

            <!-- 4. Endelea Kutazama Tovuti Button (Clean White/Border) -->
            <button type="button" 
                    onclick="window.tevdaCloseLanguageModal()"
                    class="inline-flex items-center justify-center px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl font-semibold text-sm sm:text-base text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 hover:border-slate-400 active:scale-95 transition-all duration-150 cursor-pointer">
                <span>Endelea kutazama tovuti</span>
            </button>
        </div>

    </div>
</div>

<script>
(function() {
    // Pure Vanilla JS Controller - zero external CDN dependency
    function showModal() {
        var modal = document.getElementById('tevdaLanguageModal');
        var card = document.getElementById('tevdaLanguageModalCard');
        if (!modal) return;
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    }

    function hideModal() {
        var modal = document.getElementById('tevdaLanguageModal');
        var card = document.getElementById('tevdaLanguageModalCard');
        if (!modal) return;
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
    }

    window.tevdaOpenLanguageModal = window.tevdaOpenLangModal = function() {
        showModal();
    };

    window.tevdaCloseLanguageModal = function() {
        try {
            sessionStorage.setItem('tevda_lang_prompt_seen', '1');
        } catch(e) {}
        hideModal();
    };

    window.tevdaSelectLanguage = function(langCode, switchUrl) {
        try {
            sessionStorage.setItem('tevda_lang_prompt_seen', '1');
            localStorage.setItem('tevda_locale_chosen', '1');
            localStorage.setItem('tevda_locale', langCode);
            if (window.tevdaSetLanguage) {
                window.tevdaSetLanguage(langCode);
            }
        } catch(e) {}
        window.location.href = switchUrl;
    };

    window.addEventListener('open-language-modal', function() {
        showModal();
    });

    // Check if the popout should be shown when the system is opened
    function checkAutoOpen() {
        var urlParams = new URLSearchParams(window.location.search);
        var forceOpen = urlParams.has('choose_language') || urlParams.has('lang_modal') || urlParams.has('select_language');
        
        var seenInSession = false;
        try {
            seenInSession = sessionStorage.getItem('tevda_lang_prompt_seen') === '1';
        } catch(e) {}

        // If forced via URL or user just opened the system in this session: POP OUT!
        if (forceOpen || !seenInSession) {
            setTimeout(function() {
                showModal();
            }, 300);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkAutoOpen);
    } else {
        checkAutoOpen();
    }
})();
</script>
