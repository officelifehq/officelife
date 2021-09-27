<?php

namespace Tests\Unit\ViewHelpers\Company\HR;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\HR\CompanyHRPositionShowViewHelper;

class CompanyHRPositionShowViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_detail_about_a_specific_position(): void
    {
        $company = Company::factory()->create();

        $position = Position::factory()->create([
            'title' => 'dev',
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);
        $dwight = Employee::factory()->create([
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);

        $array = CompanyHRPositionShowViewHelper::show($company, $position);

        $this->assertEquals(
            [
                'id' => $position->id,
                'title' => 'dev',
            ],
            $array['position']
        );

        $this->assertEquals(
            $michael->id,
            $array['employees']->toArray()[0]['id']
        );

        $this->assertEquals(
            $dwight->id,
            $array['employees']->toArray()[1]['id']
        );

        $this->assertEquals(
            [
                'id' => $michael->pronoun->id,
                'label' => trans($michael->pronoun->translation_key),
                'number_of_employees' => 1,
                'percent' => 50,
            ],
            $array['pronouns']->toArray()[0]
        );
    }
}
