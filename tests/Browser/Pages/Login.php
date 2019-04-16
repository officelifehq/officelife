<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use App\Models\User\User;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@email' => 'input[name=email]',
            '@password' => 'input[name=password]',
        ];
    }

    /**
     * Sign in as an admin.
     *
     * @param  \Laravel\Dusk\Browser $browser
     * @return void
     */
    public function logAsAdmin(Browser $browser)
    {
        $user = factory(User::class)->create([]);

        $browser->waitForText('Login')
            ->type('@email', $user->email)
            ->type('@password', 'secret')
            ->click('@login-button')
            ->pause(1000);
    }
}
