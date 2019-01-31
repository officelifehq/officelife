<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Factory as Faker;

class RegistrationTest extends DuskTestCase
{
    /** @test */
    public function it_lets_you_register()
    {
        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker) {
            $browser->visit('/signup')
                ->type('subdomain', $faker->word)
                ->type('email', $faker->unique()->safeEmail)
                ->type('password', 'secret')
                ->press('@signup-button')
                ->assertPathIs('/dashboard')
                ->click('@logout-button')
                ->assertPathIs('/signup');
        });
    }
}
