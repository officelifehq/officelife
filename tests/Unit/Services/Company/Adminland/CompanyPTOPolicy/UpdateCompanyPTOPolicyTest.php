<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyPTOPolicy;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\CompanyPTOPolicy\UpdateCompanyPTOPolicy;

class UpdateCompanyPTOPolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_company_pto_policy() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $ptoPolicy = factory(CompanyPTOPolicy::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_pto_policy_id' => $ptoPolicy->id,
            'total_worked_days' => 100,
            'default_amount_of_allowed_holidays' => 100,
            'default_amount_of_sick_days' => 100,
            'default_amount_of_pto_days' => 100,
        ];

        $ptoPolicy = (new UpdateCompanyPTOPolicy)->execute($request);

        $this->assertDatabaseHas('company_pto_policies', [
            'id' => $ptoPolicy->id,
            'company_id' => $michael->company_id,
            'total_worked_days' => 100,
            'default_amount_of_allowed_holidays' => 100,
            'default_amount_of_sick_days' => 100,
            'default_amount_of_pto_days' => 100,
        ]);

        $this->assertInstanceOf(
            CompanyPTOPolicy::class,
            $ptoPolicy
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ptoPolicy) {
            return $job->auditLog['action'] === 'company_pto_policy_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_pto_policy_id' => $ptoPolicy->id,
                    'company_pto_policy_year' => $ptoPolicy->year,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateCompanyPTOPolicy)->execute($request);
    }
}
