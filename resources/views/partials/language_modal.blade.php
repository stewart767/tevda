@php
    $currentLocale = app()->getLocale();
    $localeChosen = (bool) (session('locale_chosen') || request()->cookie('tevda_locale_chosen'));
    $swUrl = route('locale.switch', ['locale' => 'sw', 'return_url' => request()->fullUrl()]);
    $enUrl = route('locale.switch', ['locale' => 'en', 'return_url' => request()->fullUrl()]);
    $zhUrl = route('locale.switch', ['locale' => 'zh', 'return_url' => request()->fullUrl()]);
@endphp

<!-- TEVDA Strict Language Selection Gateway Modal -->
<div id="tevdaLanguageModal" 
     class="fixed inset-0 z-[999999] notranslate flex items-center justify-center p-4 sm:p-6 md:p-8 transition-opacity duration-300 opacity-0 pointer-events-none select-none"
     role="dialog"
     aria-modal="true"
     aria-labelledby="tevda-language-modal-title">

    <!-- Dark Blurred Backdrop (Strictly non-dismissible: clicking does NOT close modal) -->
    <div id="tevdaLanguageModalBackdrop" 
         class="fixed inset-0 bg-slate-950/85 backdrop-blur-md transition-opacity duration-300">
    </div>

    <!-- Centered Modal Card -->
    <div id="tevdaLanguageModalCard" 
         class="relative w-full max-w-lg sm:max-w-xl bg-white rounded-3xl sm:rounded-[32px] shadow-2xl border border-slate-100 p-6 sm:p-10 text-center transform scale-95 transition-all duration-300 z-10 pointer-events-auto">

        <!-- Top-Right Close Button (ONLY visible when language was previously chosen and opened manually via header) -->
        <button type="button" 
                id="tevdaLanguageModalCloseBtn"
                onclick="window.tevdaDismissLanguageModalIfAllowed()"
                class="hidden absolute top-4 right-4 sm:top-6 sm:right-6 p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors cursor-pointer"
                title="Funga / Close / 关闭">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Top Circular Icon Badge with Green Checkmark -->
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-xs">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Main Title (Clean & Minimal) -->
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
                <span>Kiswahili</span>
            </button>

            <!-- 2. English Button (Royal Blue) -->
            <button type="button" 
                    id="btnLangEn"
                    onclick="window.tevdaSelectLanguage('en', '{{ $enUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-blue-600 hover:bg-blue-700 active:scale-95 shadow-md shadow-blue-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>English</span>
            </button>

            <!-- 3. Chinese Button (Vibrant Coral / Red) -->
            <button type="button" 
                    id="btnLangZh"
                    onclick="window.tevdaSelectLanguage('zh', '{{ $zhUrl }}')"
                    class="inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base text-white bg-rose-600 hover:bg-rose-700 active:scale-95 shadow-md shadow-rose-600/25 transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                <span>中文 (Chinese)</span>
            </button>
        </div>

    </div>
</div>

<script>
(function() {
    var isModalActive = false;

    function hasChosenLanguage() {
        var serverChosen = {{ $localeChosen ? 'true' : 'false' }};
        if (serverChosen) return true;
        try {
            if (localStorage.getItem('tevda_locale_chosen') === '1') return true;
            if (document.cookie.indexOf('tevda_locale_chosen=1') !== -1) return true;
        } catch(e) {}
        return false;
    }

    function showModal() {
        var modal = document.getElementById('tevdaLanguageModal');
        var card = document.getElementById('tevdaLanguageModalCard');
        var closeBtn = document.getElementById('tevdaLanguageModalCloseBtn');
        if (!modal) return;

        isModalActive = true;

        // If user has already chosen previously and explicitly opened from menu, allow close button
        if (closeBtn) {
            if (hasChosenLanguage()) {
                closeBtn.classList.remove('hidden');
            } else {
                closeBtn.classList.add('hidden');
            }
        }

        // Lock background scroll completely so user cannot browse behind modal
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }

        // Focus first language button
        setTimeout(function() {
            var swBtn = document.getElementById('btnLangSw');
            if (swBtn) swBtn.focus();
        }, 100);
    }

    function hideModal() {
        var modal = document.getElementById('tevdaLanguageModal');
        var card = document.getElementById('tevdaLanguageModalCard');
        if (!modal) return;

        isModalActive = false;

        // Restore background scroll
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';

        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
    }

    // Explicit manual open (e.g. from header dropdown)
    window.tevdaOpenLanguageModal = window.tevdaOpenLangModal = function() {
        showModal();
    };

    // Close only allowed if user has ALREADY chosen language previously
    window.tevdaDismissLanguageModalIfAllowed = function() {
        if (hasChosenLanguage()) {
            hideModal();
        }
    };

    // Language Selection handler - the ONLY way to proceed for new visitors
    window.tevdaSelectLanguage = function(langCode, switchUrl) {
        try {
            localStorage.setItem('tevda_locale_chosen', '1');
            localStorage.setItem('tevda_locale', langCode);
            sessionStorage.setItem('tevda_lang_prompt_seen', '1');

            // Set cookie for 5 years
            var maxAge = 60 * 60 * 24 * 365 * 5;
            document.cookie = 'tevda_locale_chosen=1; path=/; max-age=' + maxAge + '; SameSite=Lax';
            document.cookie = 'tevda_locale=' + langCode + '; path=/; max-age=' + maxAge + '; SameSite=Lax';

            if (window.tevdaSetLanguage) {
                window.tevdaSetLanguage(langCode);
            }
        } catch(e) {}

        // Restore scroll and navigate
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        window.location.href = switchUrl;
    };

    // Prevent ESC key from closing if language is NOT chosen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isModalActive) {
            e.preventDefault();
            if (hasChosenLanguage()) {
                hideModal();
            }
        }
    });

    window.addEventListener('open-language-modal', function() {
        showModal();
    });

    // Check if modal must pop out
    function checkRequirement() {
        var urlParams = new URLSearchParams(window.location.search);
        var forceOpen = urlParams.has('choose_language') || urlParams.has('lang_modal') || urlParams.has('select_language');
        
        // If user has not chosen language yet OR force open requested: POP OUT & BLOCK!
        if (forceOpen || !hasChosenLanguage()) {
            setTimeout(function() {
                showModal();
            }, 150);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkRequirement);
    } else {
        checkRequirement();
    }
})();
</script>
