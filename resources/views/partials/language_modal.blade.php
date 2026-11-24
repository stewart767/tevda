@php
    $currentLocale = app()->getLocale();
    $swUrl = route('locale.switch', ['locale' => 'sw', 'return_url' => request()->fullUrl()]);
    $enUrl = route('locale.switch', ['locale' => 'en', 'return_url' => request()->fullUrl()]);
    $isChosen = $hasChosenLocale ?? false;
@endphp

<!-- Strict Mandatory Lock Styles & Pre-execution Script -->
<style>
/* Strict Mandatory Language Lock: Zero browsing allowed until language is chosen */
html.tevda-lang-locked,
html.tevda-lang-locked body,
body.tevda-modal-locked {
    overflow: hidden !important;
    height: 100vh !important;
    max-height: 100vh !important;
    touch-action: none !important;
    overscroll-behavior: none !important;
}

html.tevda-lang-locked body > :not(#tevdaLanguageModal),
body.tevda-modal-locked > :not(#tevdaLanguageModal) {
    pointer-events: none !important;
    user-select: none !important;
    -webkit-user-select: none !important;
    filter: blur(14px) brightness(0.4) grayscale(30%) !important;
    transition: filter 0.25s ease-out;
}

#tevdaLanguageModal {
    pointer-events: auto !important;
}

/* Smooth Entrance Keyframe Animation */
@keyframes tevdaModalPop {
    0% {
        opacity: 0;
        transform: scale(0.94) translateY(12px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
.tevda-modal-animate {
    animation: tevdaModalPop 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<script>
// Immediate inline check to prevent any background flash or interaction
(function() {
    try {
        var localChosen = localStorage.getItem('tevda_locale_chosen');
        var localLang = localStorage.getItem('tevda_locale');
        var supported = ['en', 'sw', 'zh'];
        var cookieMatch = document.cookie.match(/(^|;\s*)tevda_locale_chosen=([^;]*)/);
        var hasCookie = cookieMatch && cookieMatch[2] === '1';

        var isValid = (localChosen === '1' && localLang && supported.indexOf(localLang) !== -1) || hasCookie;
        if (!isValid) {
            document.documentElement.classList.add('tevda-lang-locked');
            if (document.body) {
                document.body.classList.add('tevda-modal-locked');
            }
        } else {
            document.documentElement.classList.add('tevda-lang-chosen');
        }
    } catch(e) {}
})();
</script>

<!-- TEVDA Mandatory Language Selection Modal -->
<div id="tevdaLanguageModal" 
     class="{{ $isChosen ? 'hidden' : 'flex' }} fixed inset-0 z-[999999] notranslate items-center justify-center p-4 sm:p-6 overflow-y-auto"
     style="{{ $isChosen ? 'display: none;' : 'display: flex;' }}"
     role="dialog"
     aria-modal="true"
     aria-labelledby="tevda-modal-title"
     aria-describedby="tevda-modal-desc">

    <!-- Strict Non-Dismissible Dark Backdrop (Clicking outside does NOTHING) -->
    <div id="tevdaLanguageModalBackdrop" 
         class="fixed inset-0 bg-[#001428]/95 backdrop-blur-md transition-opacity duration-300">
    </div>

    <!-- Centered Institutional Modal Card with TEVDA Branding -->
    <div id="tevdaLanguageModalCard" 
         class="tevda-modal-animate relative w-full max-w-lg bg-white rounded-3xl sm:rounded-[36px] shadow-2xl border border-stone-200 p-6 sm:p-9 text-center z-10 overflow-hidden">

        <!-- Top TEVDA Emblem / Logo -->
        <div class="mb-4">
            @if(\App\Models\Setting::hasCustomLogo())
                <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA" class="h-16 sm:h-20 w-auto max-w-[140px] object-contain mx-auto drop-shadow-md">
            @else
                <div class="relative mx-auto w-20 h-20 sm:w-22 sm:h-22 rounded-3xl bg-[#002040] border-2 border-[#108010] flex items-center justify-center text-white shadow-xl shadow-[#002040]/30">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-[#108010] to-[#E5B51B] rounded-3xl blur-xs opacity-50"></div>
                    <div class="relative flex flex-col items-center justify-center">
                        <span class="text-2xl sm:text-3xl font-black tracking-tighter text-[#E5B51B] font-heading leading-none">TEV</span>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-emerald-400 mt-0.5">TEVDA</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Mandatory Selection Pill with Active Indicator -->
        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#108010] bg-[#f2fbf2] border border-[#108010]/30 px-4 py-1.5 rounded-full mb-3 shadow-2xs">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#108010] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#108010]"></span>
            </span>
            <span>Chaguo la Lazima • Mandatory Selection</span>
        </div>

        <!-- Headings: Welcome to TEVDA & Karibu TEVDA -->
        <h2 id="tevda-modal-title" class="text-2xl sm:text-3xl font-black text-[#002040] font-heading tracking-tight mb-1">
            Welcome to TEVDA
        </h2>
        <div class="text-base sm:text-lg font-bold text-[#108010] font-heading mb-2">
            Karibu TEVDA
        </div>

        <!-- Supporting Text -->
        <p id="tevda-modal-desc" class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-sm mx-auto mb-6 sm:mb-7 font-normal">
            Please select your preferred language to continue.
            <span class="block text-slate-500 font-medium mt-0.5">Tafadhali chagua lugha unayopendelea ili kuendelea.</span>
        </p>

        <!-- Two Prominent Language Selection Cards: English & Kiswahili -->
        <div class="space-y-3 sm:space-y-3.5 mb-2 text-left" role="radiogroup" aria-label="Language Options">
            
            <!-- 1. ENGLISH CARD -->
            <button type="button" 
                    id="btnLangEn"
                    role="radio"
                    aria-checked="{{ $currentLocale === 'en' ? 'true' : 'false' }}"
                    tabindex="0"
                    onclick="window.tevdaSelectLanguage('en', '{{ $enUrl }}')"
                    class="lang-choice-card w-full flex items-center justify-between p-4 sm:p-5 rounded-2xl border-2 border-stone-200 hover:border-[#002040] bg-white hover:bg-slate-50 transition-all duration-200 shadow-xs hover:shadow-lg cursor-pointer group focus:outline-none focus:ring-2 focus:ring-[#002040]">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <span class="text-3xl sm:text-4xl leading-none shrink-0 group-hover:scale-110 transition-transform">🇬🇧</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-black text-[#002040] font-heading transition-colors">English</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#002040] bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">Official</span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium block mt-0.5">Continue in English.</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="lang-spinner hidden text-[#002040] animate-spin text-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 group-hover:bg-[#002040] text-slate-400 group-hover:text-white flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </button>

            <!-- 2. KISWAHILI CARD -->
            <button type="button" 
                    id="btnLangSw"
                    role="radio"
                    aria-checked="{{ $currentLocale === 'sw' ? 'true' : 'false' }}"
                    tabindex="0"
                    onclick="window.tevdaSelectLanguage('sw', '{{ $swUrl }}')"
                    class="lang-choice-card w-full flex items-center justify-between p-4 sm:p-5 rounded-2xl border-2 border-stone-200 hover:border-[#108010] bg-white hover:bg-[#f2fbf2] transition-all duration-200 shadow-xs hover:shadow-lg cursor-pointer group focus:outline-none focus:ring-2 focus:ring-[#108010]">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <span class="text-3xl sm:text-4xl leading-none shrink-0 group-hover:scale-110 transition-transform">🇹🇿</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-black text-[#002040] group-hover:text-[#108010] font-heading transition-colors">Kiswahili</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#108010] bg-[#f2fbf2] px-2 py-0.5 rounded-md border border-[#108010]/20">Taifa</span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium block mt-0.5">Endelea kwa Kiswahili.</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="lang-spinner hidden text-[#108010] animate-spin text-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 group-hover:bg-[#108010] text-slate-400 group-hover:text-white flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </button>

        </div>

        <!-- Footer Institutional Badge -->
        <div class="pt-4 mt-4 border-t border-stone-100 text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>TEVDA Localization Standard &bull; Mfumo Rasmi</span>
        </div>

    </div>
</div>

<script>
(function() {
    var modal = document.getElementById('tevdaLanguageModal');
    var isSelecting = false;
    var supportedLocales = ['en', 'sw', 'zh'];
    var currentLocale = '{{ $currentLocale }}';

    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
        return match ? decodeURIComponent(match[3]) : null;
    }

    function isLanguagePreferenceValid() {
        var localChosen = localStorage.getItem('tevda_locale_chosen');
        var localLang = localStorage.getItem('tevda_locale');
        var cookieChosen = getCookie('tevda_locale_chosen');
        var cookieLang = getCookie('tevda_locale');
        var serverHasChosen = @json($hasChosenLocale ?? false);

        // 1. Check client localStorage (primary frontend persistence)
        if (localChosen === '1' && localLang && supportedLocales.indexOf(localLang) !== -1) {
            return true;
        }

        // 2. Check client cookie
        if (cookieChosen === '1' && cookieLang && supportedLocales.indexOf(cookieLang) !== -1) {
            return true;
        }

        // 3. Check server-side state
        if (serverHasChosen) {
            return true;
        }

        return false;
    }

    function showMandatoryModal() {
        if (!modal) modal = document.getElementById('tevdaLanguageModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.documentElement.classList.add('tevda-lang-locked');
        document.documentElement.style.overflow = 'hidden';
        if (document.body) {
            document.body.classList.add('tevda-modal-locked');
        }

        // Focus English option for immediate keyboard accessibility
        setTimeout(function() {
            var firstBtn = document.getElementById('btnLangEn');
            if (firstBtn) firstBtn.focus();
        }, 100);
    }

    function hideModalInternal() {
        if (!modal) modal = document.getElementById('tevdaLanguageModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.documentElement.classList.remove('tevda-lang-locked');
        document.documentElement.classList.add('tevda-lang-chosen');
        document.documentElement.style.overflow = '';
        if (document.body) {
            document.body.classList.remove('tevda-modal-locked');
        }
    }

    // STRICT INTERACTION RESTRICTION:
    // Manual trigger from header language switcher
    window.tevdaOpenLanguageModal = window.tevdaOpenLangModal = function() {
        showMandatoryModal();
    };

    // Public dismiss function is locked unless valid preference exists
    window.tevdaDismissLanguageModal = window.tevdaDismissLanguageModalIfAllowed = function() {
        if (isLanguagePreferenceValid()) {
            hideModalInternal();
        }
    };

    // Handle language selection
    window.tevdaSelectLanguage = function(langCode, switchUrl) {
        if (isSelecting) return;
        
        // Validate against official supported languages
        if (supportedLocales.indexOf(langCode) === -1) {
            langCode = 'en';
        }

        isSelecting = true;

        // Show active visual feedback on the chosen card
        var activeBtn = null;
        if (langCode === 'en') activeBtn = document.getElementById('btnLangEn');
        else if (langCode === 'sw') activeBtn = document.getElementById('btnLangSw');

        if (activeBtn) {
            var spinner = activeBtn.querySelector('.lang-spinner');
            if (spinner) spinner.classList.remove('hidden');
            activeBtn.classList.add('border-[#108010]', 'bg-[#f2fbf2]');
        }

        // 1. Immediately persist language in localStorage and cookies (5 years)
        try {
            var maxAge = 60 * 60 * 24 * 365 * 5;
            localStorage.setItem('tevda_locale', langCode);
            localStorage.setItem('tevda_locale_chosen', '1');
            sessionStorage.setItem('tevda_lang_prompt_seen', '1');

            document.cookie = 'tevda_locale=' + langCode + '; path=/; max-age=' + maxAge + '; SameSite=Lax';
            document.cookie = 'tevda_locale_chosen=1; path=/; max-age=' + maxAge + '; SameSite=Lax';
            document.cookie = 'tevda_locale=' + langCode + '; path=/tevda; max-age=' + maxAge + '; SameSite=Lax';
            document.cookie = 'tevda_locale_chosen=1; path=/tevda; max-age=' + maxAge + '; SameSite=Lax';

            // Clear residual googtrans
            document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/tevda;';
        } catch(e) {}

        // 2. IMMEDIATELY CLOSE THE MODAL so the user enters the site without waiting or sticking
        hideModalInternal();

        // 3. If selected language is already active on the current page, no redirect is necessary!
        if (currentLocale === langCode) {
            if (window.fetch) {
                fetch(switchUrl, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    credentials: 'same-origin'
                }).catch(function(){});
            }
            isSelecting = false;
            return;
        }

        // 4. If selected language is different, smoothly redirect to localized switch URL
        setTimeout(function() {
            window.location.href = switchUrl;
        }, 80);
    };

    // STRICT INTERACTION RESTRICTION: Block Escape Key
    window.addEventListener('keydown', function(e) {
        if (modal && modal.style.display !== 'none') {
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            // Keyboard navigation between language choices (Arrow Up/Down or Tab cycle)
            var buttons = [
                document.getElementById('btnLangEn'),
                document.getElementById('btnLangSw')
            ].filter(Boolean);

            if (buttons.length > 0) {
                var currentIndex = buttons.indexOf(document.activeElement);
                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    var nextIndex = (currentIndex + 1) % buttons.length;
                    buttons[nextIndex].focus();
                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    var prevIndex = (currentIndex - 1 + buttons.length) % buttons.length;
                    buttons[prevIndex].focus();
                } else if (e.key === 'Tab') {
                    if (e.shiftKey && (currentIndex <= 0 || currentIndex === -1)) {
                        e.preventDefault();
                        buttons[buttons.length - 1].focus();
                    } else if (!e.shiftKey && currentIndex >= buttons.length - 1) {
                        e.preventDefault();
                        buttons[0].focus();
                    }
                }
            }
        }
    }, true);

    // Strictly prevent background scroll via mouse wheel or mobile touchmove
    window.addEventListener('wheel', function(e) {
        if (document.documentElement.classList.contains('tevda-lang-locked') || (document.body && document.body.classList.contains('tevda-modal-locked'))) {
            var card = document.getElementById('tevdaLanguageModalCard');
            if (card && !card.contains(e.target)) {
                e.preventDefault();
            }
        }
    }, { passive: false });

    window.addEventListener('touchmove', function(e) {
        if (document.documentElement.classList.contains('tevda-lang-locked') || (document.body && document.body.classList.contains('tevda-modal-locked'))) {
            var card = document.getElementById('tevdaLanguageModalCard');
            if (card && !card.contains(e.target)) {
                e.preventDefault();
            }
        }
    }, { passive: false });

    // Initial check on page load:
    // If visitor has NOT previously selected a valid language, display the popup automatically.
    function checkMandatoryLanguagePopup() {
        if (!isLanguagePreferenceValid()) {
            showMandatoryModal();
        } else {
            hideModalInternal();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkMandatoryLanguagePopup);
    } else {
        checkMandatoryLanguagePopup();
    }

    // Also listen for explicit manual triggers from header language switcher
    window.addEventListener('open-language-modal', function() {
        showMandatoryModal();
    });
})();
</script>
