<?php

namespace Tests\Unit\Helpers;

use App\Helpers\LocaleHelper;
use App\Models\User\User;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LocaleHelperTest extends TestCase
{
    /** @test */
    public function it_returns_locale_english_by_default()
    {
        $this->assertEquals(
            'en',
            LocaleHelper::getLocale()
        );
    }

    /** @test */
    public function it_returns_right_locale_if_user_logged()
    {
        $user = User::factory()->create([
            'locale' => 'fr',
        ]);
        $this->be($user);

        $this->assertEquals(
            'fr',
            LocaleHelper::getLocale()
        );
    }

    /** @test */
    public function it_get_direction_default()
    {
        $this->assertEquals(
            'ltr',
            LocaleHelper::getDirection()
        );
    }

    /** @test */
    public function it_get_direction_for_french()
    {
        App::setLocale('fr');

        $this->assertEquals(
            'ltr',
            LocaleHelper::getDirection()
        );
    }

    /** @test */
    public function it_get_direction_for_hebrew()
    {
        App::setLocale('he');

        $this->assertEquals(
            'rtl',
            LocaleHelper::getDirection()
        );
    }

    /**
     * @test
     *
     * @dataProvider localeHelperGetLangProvider
     *
     * @param  mixed  $locale
     * @param  mixed  $expect
     */
    public function it_return_languages($locale, $expect)
    {
        $lang = LocaleHelper::getLang($locale);

        $this->assertEquals(
            $expect,
            $lang
        );
    }

    public static function localeHelperGetLangProvider()
    {
        return [
            ['en', 'en'],
            ['En', 'en'],
            ['EN', 'en'],
            ['en-US', 'en'],
            ['en-us', 'en'],
            ['en_US', 'en'],
            ['pt-BR', 'pt'],
            ['xx-YY', 'xx'],
        ];
    }

    /** @test */
    public function it_return_languages_list()
    {
        $languages = LocaleHelper::getLocaleList();

        $this->assertContains([
            'lang' => 'en',
            'name' => 'English',
            'name-orig' => 'English',
        ], $languages);
    }
}
