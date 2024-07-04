<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use App\Http\ViewHelpers\Adminland\AdminECoffeeViewHelper;
use App\Models\Company\Company;
use Tests\TestCase;

class AdminECoffeeViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_information_about_the_ecoffee_process(): void
    {
        $company = Company::factory()->create([
            'e_coffee_enabled' => true,
        ]);

        $array = AdminECoffeeViewHelper::eCoffee($company);

        $this->assertEquals(
            [
                'enabled' => true,
            ],
            $array
        );
    }
}
