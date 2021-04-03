<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Company;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyPTOPolicyTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $policy = CompanyPTOPolicy::factory()->create([]);
        $this->assertTrue($policy->company()->exists());
    }

    /** @test */
    public function it_has_many_calendars(): void
    {
        $policy = CompanyPTOPolicy::factory()->create([]);
        CompanyCalendar::factory()->count(2)->create([
            'company_pto_policy_id' => $policy->id,
        ]);

        $this->assertTrue($policy->calendars()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $dunder = Company::factory()->create([]);
        $ptoPolicy = CompanyPTOPolicy::factory()->create([
            'company_id' => $dunder->id,
            'year' => 2020,
            'total_worked_days' => 250,
            'default_amount_of_allowed_holidays' => 30,
            'default_amount_of_sick_days' => 3,
            'default_amount_of_pto_days' => 5,
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $ptoPolicy->id,
                'company' => [
                    'id' => $dunder->id,
                ],
                'year' => 2020,
                'total_worked_days' => 250,
                'default_amount_of_allowed_holidays' => 30,
                'default_amount_of_sick_days' => 3,
                'default_amount_of_pto_days' => 5,
                'created_at' => '2020-01-12 00:00:00',
            ],
            $ptoPolicy->toObject()
        );
    }
}
