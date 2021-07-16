<?php

namespace Tests\Unit\ViewHelpers\Company\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\Position;
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

    /** @test */
    public function it_gets_the_stats_about_the_pronouns_used_in_the_company(): void
    {
        $company = Company::factory()->create();
        Pronoun::all()->each(function (Pronoun $pronoun) {
            $pronoun->delete();
        });

        $pronounMale = Pronoun::factory()->create([
            'translation_key' => 'he/him',
        ]);
        $pronounFemale = Pronoun::factory()->create([
            'translation_key' => 'female',
        ]);
        Employee::factory()->count(2)->create([
            'company_id' => $company->id,
            'pronoun_id' => $pronounMale->id,
        ]);
        Employee::factory()->create([
            'company_id' => $company->id,
            'pronoun_id' => $pronounFemale->id,
        ]);

        $array = CompanyHRViewHelper::genderStats($company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $pronounMale->id,
                    'label' => 'he/him',
                    'number_of_employees' => 2,
                    'percent' => 67,
                ],
                1 => [
                    'id' => $pronounFemale->id,
                    'label' => 'female',
                    'number_of_employees' => 1,
                    'percent' => 33,
                ],
                2 => [
                    'id' => 0,
                    'label' => 'No gender',
                    'number_of_employees' => 0,
                    'percent' => 0,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_stats_about_the_positions(): void
    {
        $company = Company::factory()->create();

        $positionA = Position::factory()->create([
            'title' => 'dev',
        ]);
        $positionB = Position::factory()->create([
            'title' => 'ceo',
        ]);
        Employee::factory()->count(2)->create([
            'company_id' => $company->id,
            'position_id' => $positionA->id,
        ]);
        Employee::factory()->create([
            'company_id' => $company->id,
            'position_id' => $positionB->id,
        ]);

        $array = CompanyHRViewHelper::positions($company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $positionA->id,
                    'title' => 'dev',
                    'number_of_employees' => 2,
                    'percent' => 67,
                    'url' => env('APP_URL') . '/' . $company->id . '/company/hr/positions/'.$positionA->id,
                ],
                1 => [
                    'id' => $positionB->id,
                    'title' => 'ceo',
                    'number_of_employees' => 1,
                    'percent' => 33,
                    'url' => env('APP_URL') . '/' . $company->id . '/company/hr/positions/' . $positionB->id,
                ],
            ],
            $array
        );
    }
}
