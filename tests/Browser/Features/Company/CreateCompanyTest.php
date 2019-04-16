<?php

namespace Tests\Browser\Features\Registration;

use Tests\DuskTestCase;
use App\Models\User\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateCompanyTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_lets_you_create_a_company()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->logAsAdmin('Login')
                ->pause(1000)
                ->click('@create-company-blank-state')
                ->waitForText('Create a company')
                ->type('name', 'Dunder Mifflin')
                ->click('@create-button')
                ->pause(1000)
                ->assertSee('Dunder Mifflin');
        });

        // check that we can't create a company with the same name
        $this->browse(function (Browser $browser) {
            $browser->visit('/home')
                ->click('@create-company')
                ->waitForText('Create a company')
                ->type('name', 'Dunder Mifflin')
                ->click('@create-button')
                ->pause(1000)
                ->assertSee('The name has already been taken')
                ->visit('/logout');
        });
    }
}
