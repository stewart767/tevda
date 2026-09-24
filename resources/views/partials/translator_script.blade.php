@php
    $currentLocale = app()->getLocale();
@endphp

<!-- Google Translate Silent Element (Hidden) -->
<div id="google_translate_element" style="display:none;position:absolute;top:-9999px;left:-9999px;"></div>

<style>
    /* Completely hide Google Translate default banners and tooltips */
    .goog-te-banner-frame.skiptranslate, 
    .goog-te-banner-frame, 
    iframe.goog-te-banner-frame, 
    .goog-te-menu-frame,
    #goog-gt-tt, 
    .goog-te-balloon-frame,
    .goog-tooltip, 
    .goog-tooltip:hover { 
        display: none !important; 
        visibility: hidden !important; 
    }
    body { 
        top: 0px !important; 
        position: static !important; 
    }
    .goog-te-gadget { 
        display: none !important; 
    }
    .goog-text-highlight { 
        background-color: transparent !important; 
        border: none !important; 
        box-shadow: none !important; 
    }
    font[style] {
        background-color: transparent !important;
        box-shadow: none !important;
    }
    .notranslate {
        translate: no;
    }
</style>

<script>
/**
 * TEVDA Comprehensive Dual-Engine Multilingual System
 * Server-Side Locale + Real-Time Live DOM Translation
 */
(function() {
    const currentLocale = '{{ $currentLocale }}';
    
    // Cookie Helper
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        const hostname = window.location.hostname;
        document.cookie = name + "=" + (value || "")  + expires + "; path=/;";
        if (hostname && hostname !== 'localhost' && !hostname.includes('127.0.0.1')) {
            document.cookie = name + "=" + (value || "")  + expires + "; path=/; domain=" + hostname;
        }
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Language mapper for Google Translate
    window.tevdaSetLanguage = function(langCode) {
        let gtVal = '/en/en';
        if (langCode === 'sw') {
            gtVal = '/en/sw';
        } else if (langCode === 'zh' || langCode === 'zh-cn' || langCode === 'zh_CN') {
            gtVal = '/en/zh-CN';
        }
        
        setCookie('tevda_locale', langCode, 365);
        setCookie('googtrans', gtVal, 365);
    };

    // Auto-sync cookie with current server locale if out of sync
    const expectedGtVal = currentLocale === 'sw' ? '/en/sw' : (currentLocale === 'zh' ? '/en/zh-CN' : '/en/en');
    const existingGtCookie = getCookie('googtrans');
    if (existingGtCookie !== expectedGtVal) {
        setCookie('googtrans', expectedGtVal, 365);
    }

    // Google Translate Initialization Handler
    window.tevdaGoogleTranslateInit = function() {
        try {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,sw,zh-CN',
                autoDisplay: false,
                multilanguagePage: true
            }, 'google_translate_element');
        } catch (e) {
            console.warn('TEVDA Translate Engine Init Notice:', e);
        }
    };

    // Load Google Translate Script if translation needed or active
    if (!window.google || !window.google.translate) {
        const gtScript = document.createElement('script');
        gtScript.type = 'text/javascript';
        gtScript.async = true;
        gtScript.src = '//translate.google.com/translate_a/element.js?cb=tevdaGoogleTranslateInit';
        document.head.appendChild(gtScript);
    }
})();
</script>
