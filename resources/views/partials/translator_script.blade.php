<style>
    /* Completely hide and eliminate any Google Translate banner, bar, tooltip, or iframe */
    .goog-te-banner-frame.skiptranslate, 
    .goog-te-banner-frame, 
    iframe.goog-te-banner-frame,
    iframe[id*=":1.container"],
    iframe[id*="google"],
    iframe[class*="goog"],
    .goog-te-menu-frame,
    .VIpgJd-ZVi9od-ORHb-OEVmcd,
    .VIpgJd-ZVi9od-ORHb,
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
    .VIpgJd-ZVi9od-l4eHX-hSRLGd,
    #goog-gt-tt, 
    .goog-te-balloon-frame,
    .goog-tooltip, 
    .goog-tooltip:hover,
    .goog-te-gadget,
    #google_translate_element { 
        display: none !important; 
        visibility: hidden !important; 
        height: 0 !important;
        width: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
        position: absolute !important;
        top: -9999px !important;
        left: -9999px !important;
    }
    html, body { 
        top: 0px !important; 
        position: static !important; 
    }
    .goog-text-highlight { 
        background-color: transparent !important; 
        border: none !important; 
        box-shadow: none !important; 
    }
</style>

<script>
/**
 * TEVDA Native Localization Controller
 * Removes third-party Google Translate top bar and manages clean language preferences
 */
(function() {
    // Delete any residual googtrans cookie
    function purgeGoogleCookies() {
        var cookies = ['googtrans', 'googtrans_cache'];
        var domains = [window.location.hostname, '.' + window.location.hostname, ''];
        var paths = ['/', '/tevda', '/tevda/'];

        cookies.forEach(function(cookieName) {
            domains.forEach(function(domain) {
                paths.forEach(function(path) {
                    var str = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=' + path;
                    if (domain) str += '; domain=' + domain;
                    document.cookie = str;
                });
            });
        });

        // Remove any injected Google iframes/banners from the DOM
        try {
            var googleFrames = document.querySelectorAll('.goog-te-banner-frame, iframe[name*="google"], iframe[class*="goog"], .VIpgJd-ZVi9od-ORHb-OEVmcd, #goog-gt-tt');
            googleFrames.forEach(function(frame) {
                if (frame && frame.parentNode) {
                    frame.parentNode.removeChild(frame);
                }
            });
            document.body.style.top = '0px';
        } catch(e) {}
    }

    purgeGoogleCookies();
    window.addEventListener('DOMContentLoaded', purgeGoogleCookies);
    window.addEventListener('load', purgeGoogleCookies);

    // Global Language setter helper
    window.tevdaSetLanguage = function(langCode) {
        purgeGoogleCookies();
        try {
            localStorage.setItem('tevda_locale', langCode);
            localStorage.setItem('tevda_locale_chosen', '1');
            sessionStorage.setItem('tevda_lang_prompt_seen', '1');
        } catch(e) {}
    };

    // Helper to open the language selection modal
    window.tevdaOpenLanguageModal = window.tevdaOpenLangModal = function() {
        if (typeof window.tevdaOpenLangModal === 'function') {
            // Already handled
        }
        window.dispatchEvent(new CustomEvent('open-language-modal'));
    };
})();
</script>
