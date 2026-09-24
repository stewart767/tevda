<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\LocaleController;

class SetLocale
{
    /**
     * Handle an incoming request and set the active application locale.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(LocaleController::$supportedLocales);
        $locale = null;

        // 1. Check if user provided ?lang= or ?locale= query string
        if ($request->has('lang')) {
            $langParam = strtolower(trim($request->query('lang')));
            if (in_array($langParam, ['zh-cn', 'zh_cn', 'chinese', 'cn'])) {
                $langParam = 'zh';
            } elseif (in_array($langParam, ['swahili', 'tz'])) {
                $langParam = 'sw';
            }
            if (in_array($langParam, $supported)) {
                $locale = $langParam;
                Session::put('locale', $locale);
                Cookie::queue('tevda_locale', $locale, 60 * 24 * 365 * 5);
            }
        }

        // 2. Check Session
        if (!$locale && Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            if (in_array($sessionLocale, $supported)) {
                $locale = $sessionLocale;
            }
        }

        // 3. Check Cookie
        if (!$locale && $request->hasCookie('tevda_locale')) {
            $cookieLocale = $request->cookie('tevda_locale');
            if (in_array($cookieLocale, $supported)) {
                $locale = $cookieLocale;
                Session::put('locale', $locale);
            }
        }

        // 4. Fallback to default
        if (!$locale || !in_array($locale, $supported)) {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);

        // Share locale variables across all views
        View::share('activeLocale', $locale);
        View::share('activeLocaleInfo', LocaleController::$supportedLocales[$locale] ?? LocaleController::$supportedLocales['en']);
        View::share('allLocales', LocaleController::$supportedLocales);

        return $next($request);
    }
}
