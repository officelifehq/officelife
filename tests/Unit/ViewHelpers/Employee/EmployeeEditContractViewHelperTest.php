<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\ConsultantRate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeEditContractViewHelper;

class EmployeeEditContractViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_the_contract(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $michael->contract_renewed_at = '2020-10-10 00:00:00';
        $michael->save();
        $array = EmployeeEditContractViewHelper::employeeInformation($michael);

        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'year' => 2020,
                'month' => 10,
                'day' => 10,
                'max_year' => 2028,
            ],
            $array
        );

        $dwight = $this->createAdministrator();
        $array = EmployeeEditContractViewHelper::employeeInformation($dwight);

        $this->assertEquals(
            [
                'id' => $dwight->id,
                'name' => $dwight->name,
                'year' => null,
                'month' => null,
                'day' => null,
                'max_year' => 2028,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_a_collection_of_rates(): void
    {
        $michael = $this->createAdministrator();
        $rateActive = ConsultantRate::factory()->create([
            'employee_id' => $michael->id,
            'active' => true,
        ]);
        $rateInactive = ConsultantRate::factory()->create([
            'employee_id' => $michael->id,
            'active' => false,
        ]);

        $array = EmployeeEditContractViewHelper::rates($michael, $michael->company);
        $this->assertEquals(
            'USD',
            $array['company_currency']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $rateInactive->id,
                    'rate' => $rateInactive->rate,
                    'active' => false,
                ],
                1 => [
                    'id' => $rateActive->id,
                    'rate' => $rateActive->rate,
                    'active' => true,
                ],
            ],
            $array['rates']->toArray()
        );
    }
}
