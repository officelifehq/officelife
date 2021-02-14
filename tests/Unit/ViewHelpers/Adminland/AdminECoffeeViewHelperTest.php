<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminECoffeeViewHelper;

class AdminECoffeeViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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
