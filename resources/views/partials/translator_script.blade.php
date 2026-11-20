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
