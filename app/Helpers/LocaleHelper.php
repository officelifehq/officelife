<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vluzrmos\LanguageDetector\Facades\LanguageDetector;

class LocaleHelper
{
    private const LANG_SPLIT = '/(-|_)/';

    /**
     * Get the current or default locale.
     *
     * @return string
     */
    public static function getLocale()
    {
        $locale = Auth::check() ? Auth::user()->locale : null;
        if (! $locale) {
            $locale = LanguageDetector::detect() ?: config('app.locale');
        }

        return $locale;
    }

    /**
     * Get the current lang from locale.
     *
     * @param mixed|null $locale
     * @return string  lang, lowercase form
     */
    public static function getLang($locale = null)
    {
        if (is_null($locale)) {
            $locale = App::getLocale();
        }
        if (preg_match(self::LANG_SPLIT, $locale)) {
            $locale = preg_split(self::LANG_SPLIT, $locale, 2)[0];
        }

        return mb_strtolower($locale);
    }

    /**
     * Get the list of avalaible languages.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getLocaleList()
    {
        return collect(config('lang-detector.languages'))->map(function ($lang) {
            return [
                'lang' => $lang,
                'name' => self::getLocaleName($lang),
                'name-orig' => self::getLocaleName($lang, $lang),
            ];
        });
    }

    /**
     * Get the name of one language.
     *
     * @param string $lang
     * @param string $locale
     *
     * @return string
     */
    private static function getLocaleName($lang, $locale = null): string
    {
        $name = trans('app.locale_'.$lang, [], $locale);
        if ($name === 'app.locale_'.$lang) {
            // The name of the new language is not already set, even in english
            $name = $lang; // @codeCoverageIgnore
        }

        return $name;
    }

    /**
     * Get the direction: left to right/right to left.
     *
     * @return string
     */
    public static function getDirection()
    {
        $lang = self::getLang();
        switch ($lang) {
            // Source: https://meta.wikimedia.org/wiki/Template:List_of_language_names_ordered_by_code
            // @codeCoverageIgnoreStart
            case 'ar':
            case 'arc':
            case 'dv':
            case 'fa':
            case 'ha':
            case 'he':
            case 'khw':
            case 'ks':
            case 'ku':
            case 'ps':
            case 'ur':
            case 'yi':
            // @codeCoverageIgnoreEnd
                return 'rtl';
            default:
                return 'ltr';
        }
    }
}
