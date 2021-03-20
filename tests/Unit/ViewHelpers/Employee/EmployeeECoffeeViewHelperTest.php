<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\ECoffee;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeECoffeeViewHelper;

class EmployeeECoffeeViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_all_e_coffee_sessions(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $company = $michael->company;
        $company->e_coffee_enabled = true;
        $company->save();
        $company->refresh();

        $eCoffee = ECoffee::factory()->create([
            'company_id' => $company->id,
        ]);
        ECoffeeMatch::factory()->count(3)->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
        ]);
        $match = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
        ]);

        $collection = EmployeeECoffeeViewHelper::index($michael, $company);

        $this->assertEquals(
            4,
            $collection->count()
        );

        $this->assertEquals(
            [
                'id' => $match->id,
                'ecoffee' => [
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                ],
                'with_employee' => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'first_name' => $dwight->first_name,
                    'avatar' => ImageHelper::getAvatar($dwight),
                    'position' => $dwight->position ? $dwight->position->title : null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id,
                ],
                'view_all_url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/ecoffees',
            ],
            $collection->toArray()[0]
        );
    }
}
