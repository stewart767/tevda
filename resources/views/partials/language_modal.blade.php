@php
    $currentLocale = app()->getLocale();
    $swUrl = route('locale.switch', ['locale' => 'sw', 'return_url' => request()->fullUrl()]);
    $enUrl = route('locale.switch', ['locale' => 'en', 'return_url' => request()->fullUrl()]);
    $zhUrl = route('locale.switch', ['locale' => 'zh', 'return_url' => request()->fullUrl()]);
@endphp

<!-- TEVDA Explicit Language Selection Modal (Hidden by default; never intercepts clicks when closed) -->
<div id="tevdaLanguageModal" 
     class="hidden fixed inset-0 z-[99999] notranslate items-center justify-center p-4 sm:p-6 md:p-8"
     style="display: none;"
     role="dialog"
     aria-modal="true"
     aria-labelledby="tevda-language-modal-title">

    <!-- Dark Blurred Backdrop (Clicking backdrop dismisses modal) -->
    <div id="tevdaLanguageModalBackdrop" 
         onclick="window.tevdaDismissLanguageModal()"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity duration-200 cursor-pointer">
    </div>

    <!-- Centered Modal Card -->
    <div id="tevdaLanguageModalCard" 
         class="relative w-full max-w-lg bg-white rounded-3xl sm:rounded-[32px] shadow-2xl border border-slate-100 p-6 sm:p-10 text-center transform transition-all duration-200 z-10">

        <!-- Top-Right Close Button -->
        <button type="button" 
                id="tevdaLanguageModalCloseBtn"
                onclick="window.tevdaDismissLanguageModal()"
                class="absolute top-4 right-4 sm:top-6 sm:right-6 p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors cursor-pointer"
                title="Funga / Close / 关闭">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Top Circular Icon Badge with Green Checkmark -->
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-sm">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Main Title -->
        <h2 id="tevda-language-modal-title" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-heading mb-6 sm:mb-8">
            Chagua Lugha / Choose Language
        </h2>

        <!-- Language Action Buttons Row -->
        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 mb-2">
            <!-- 1. Kiswahili Button (Emerald Green) -->
            <button type="button" 
                    id="btnLangSw"
                    onclick="window.tevdaSelectLanguage('sw', '{{ $swUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-md shadow-emerald-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>🇹🇿 Kiswahili</span>
            </button>

            <!-- 2. English Button (Royal Blue) -->
            <button type="button" 
                    id="btnLangEn"
                    onclick="window.tevdaSelectLanguage('en', '{{ $enUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-blue-600 hover:bg-blue-700 active:scale-95 shadow-md shadow-blue-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>🇬🇧 English</span>
            </button>

            <!-- 3. Chinese Button (Vibrant Coral / Red) -->
            <button type="button" 
                    id="btnLangZh"
                    onclick="window.tevdaSelectLanguage('zh', '{{ $zhUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-rose-600 hover:bg-rose-700 active:scale-95 shadow-md shadow-rose-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>🇨🇳 中文 (Chinese)</span>
            </button>
        </div>

    </div>
</div>

<script>
(function() {
    var modal = document.getElementById('tevdaLanguageModal');

    function showModal() {
        if (!modal) modal = document.getElementById('tevdaLanguageModal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        if (!modal) modal = document.getElementById('tevdaLanguageModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }

    // Public window methods for opening and closing
    window.tevdaOpenLanguageModal = window.tevdaOpenLangModal = function() {
        showModal();
    };

    window.tevdaDismissLanguageModal = window.tevdaDismissLanguageModalIfAllowed = function() {
        hideModal();
    };

    // Explicit language selection
    window.tevdaSelectLanguage = function(langCode, switchUrl) {
        try {
            localStorage.setItem('tevda_locale_chosen', '1');
            localStorage.setItem('tevda_locale', langCode);
            var maxAge = 60 * 60 * 24 * 365 * 5;
            document.cookie = 'tevda_locale_chosen=1; path=/; max-age=' + maxAge + '; SameSite=Lax';
            document.cookie = 'tevda_locale=' + langCode + '; path=/; max-age=' + maxAge + '; SameSite=Lax';
            if (window.tevdaSetLanguage) {
                window.tevdaSetLanguage(langCode);
            }
        } catch(e) {}

        hideModal();
        window.location.href = switchUrl;
    };

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display !== 'none') {
            hideModal();
        }
    });

    window.addEventListener('open-language-modal', function() {
        showModal();
    });

    // ONLY open if the user explicitly added a query parameter to open it (e.g. ?choose_language=1)
    function checkExplicitQuery() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('choose_language') || urlParams.has('lang_modal') || urlParams.has('select_language')) {
            setTimeout(showModal, 150);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkExplicitQuery);
    } else {
        checkExplicitQuery();
    }
})();
</script>
