<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyPTOPolicyTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $policy = factory(CompanyPTOPolicy::class)->create([]);
        $this->assertTrue($policy->company()->exists());
    }

    /** @test */
    public function it_has_many_calendars(): void
    {
        $policy = factory(CompanyPTOPolicy::class)->create([]);
        factory(CompanyCalendar::class, 2)->create([
            'company_pto_policy_id' => $policy->id,
        ]);

        $this->assertTrue($policy->calendars()->exists());
    }
}
