<?php

namespace Tests\Unit\ViewHelpers\Company\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\ECoffee;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\HR\CompanyHRViewHelper;

class CompanyHRViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_statistic_about_ecoffees_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // we'll create 3 ecoffee sessions, with 3 matches each (one of them being marked as happened)
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $company = $michael->company;
        $company->e_coffee_enabled = true;
        $company->save();
        $company->refresh();

        $eCoffee1 = ECoffee::factory()->create([
            'company_id' => $company->id,
            'active' => true,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee1->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => true,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee1->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => false,
        ]);
        $eCoffee2 = ECoffee::factory()->create([
            'company_id' => $company->id,
            'active' => false,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee2->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => true,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee2->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => false,
        ]);
        $eCoffee3 = ECoffee::factory()->create([
            'company_id' => $company->id,
            'active' => false,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee3->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => true,
        ]);
        ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee3->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
            'happened' => false,
        ]);

        $array = CompanyHRViewHelper::eCoffees($michael->company);

        $this->assertEquals(
            [
                'active_session' => [
                    'total' => 2,
                    'happened' => 1,
                    'percent' => 50.0,
                ],
                'last_active_session' => [
                    'total' => 2,
                    'happened' => 1,
                    'percent' => 50.0,
                ],
                'average_total_sessions' => 50.0,
                'number_of_sessions' => 3,
            ],
            $array
        );
    }
}
