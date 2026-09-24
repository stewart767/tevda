<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Supported locales list with metadata.
     */
    public static array $supportedLocales = [
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
            'flag_svg' => 'gb',
            'short' => 'EN',
            'locale_tag' => 'en-US',
            'dir' => 'ltr',
        ],
        'sw' => [
            'code' => 'sw',
            'name' => 'Kiswahili',
            'native' => 'Kiswahili',
            'flag' => '🇹🇿',
            'flag_svg' => 'tz',
            'short' => 'SW',
            'locale_tag' => 'sw-TZ',
            'dir' => 'ltr',
        ],
        'zh' => [
            'code' => 'zh',
            'name' => 'Simplified Chinese',
            'native' => '中文 (简体)',
            'flag' => '🇨🇳',
            'flag_svg' => 'cn',
            'short' => 'ZH',
            'locale_tag' => 'zh-CN',
            'dir' => 'ltr',
        ],
    ];

    /**
     * Switch application locale and redirect back to the previous page.
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, string $locale)
    {
        $locale = strtolower(trim($locale));

        // Map common aliases (e.g. zh-CN, zh_CN, swahili) to valid codes
        if (in_array($locale, ['zh-cn', 'zh_cn', 'chinese', 'cn'])) {
            $locale = 'zh';
        } elseif (in_array($locale, ['swahili', 'tz'])) {
            $locale = 'sw';
        } elseif (in_array($locale, ['english', 'gb', 'us', 'en-us', 'en_us'])) {
            $locale = 'en';
        }

        if (!array_key_exists($locale, self::$supportedLocales)) {
            $locale = 'en';
        }

        // Save in session and permanent cookies
        Session::put('locale', $locale);
        App::setLocale($locale);

        $googTransValue = match($locale) {
            'sw' => '/en/sw',
            'zh' => '/en/zh-CN',
            default => '/en/en',
        };

        $cookieTevda = Cookie::make('tevda_locale', $locale, 60 * 24 * 365 * 5, '/', null, false, false);
        $cookieGoogle = Cookie::make('googtrans', $googTransValue, 60 * 24 * 365 * 5, '/', null, false, false);

        // Redirect back immediately to current page
        $returnUrl = $request->input('return_url');
        if (!empty($returnUrl) && filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            return redirect()->to($returnUrl)->withCookie($cookieTevda)->withCookie($cookieGoogle);
        }

        return redirect()->back(fallback: route('home'))->withCookie($cookieTevda)->withCookie($cookieGoogle);
    }

    /**
     * Get details of currently active locale.
     */
    public static function getCurrentLocale(): array
    {
        $locale = App::getLocale();
        return self::$supportedLocales[$locale] ?? self::$supportedLocales['en'];
    }

    /**
     * Get all supported locales.
     */
    public static function getSupportedLocales(): array
    {
        return self::$supportedLocales;
    }
}
