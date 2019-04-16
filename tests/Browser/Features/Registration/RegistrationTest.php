<?php

namespace Tests\Browser\Features\Registration;

use Tests\DuskTestCase;
use App\Models\User\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_lets_you_register_an_account()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/signup')
                ->waitForText('Create an account now')
                ->type('email', 'admin@admin.com')
                ->type('password', 'admin')
                ->click('@register-button')
                ->pause(1000)
                ->assertSee('Create a company')
                ->visit('/logout');
        });
    }

    /** @test */
    public function it_lets_you_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->pause(1000)
                ->type('email', 'admin@admin.com')
                ->type('password', 'admin')
                ->click('@login-button')
                ->pause(1000)
                ->visit('/logout');
        });
    }

    /** @test */
    public function it_doesnt_let_you_register_if_the_email_already_exists()
    {
        $user = factory(User::class)->create([]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/signup')
                ->pause(1000)
                ->type('email', $user->email)
                ->type('password', 'admin')
                ->click('@register-button')
                ->pause(1000)
                ->assertSee('The email has already been taken.');
        });
    }
}
