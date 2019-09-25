<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyPTOPolicy;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\CompanyPTOPolicyAlreadyExistException;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class CreateCompanyPTOPolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_company_pto_policy() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2020,
            'total_worked_days' => 200,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        $ptoPolicy = (new CreateCompanyPTOPolicy)->execute($request);

        $this->assertDatabaseHas('company_pto_policies', [
            'id' => $ptoPolicy->id,
            'company_id' => $michael->company_id,
            'year' => 2020,
            'total_worked_days' => 200,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ]);

        $this->assertInstanceOf(
            CompanyPTOPolicy::class,
            $ptoPolicy
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ptoPolicy) {
            return $job->auditLog['action'] === 'company_pto_policy_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_pto_policy_id' => $ptoPolicy->id,
                    'company_pto_policy_year' => $ptoPolicy->year,
                ]);
        });
    }

    /** @test */
    public function it_throws_an_exception_if_the_policy_is_already_set_for_the_year() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2020,
            'total_worked_days' => 200,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        // creating a new one with the exact same year
        $this->expectException(CompanyPTOPolicyAlreadyExistException::class);
        (new CreateCompanyPTOPolicy)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateCompanyPTOPolicy)->execute($request);
    }
}
