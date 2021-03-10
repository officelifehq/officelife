<?php

namespace Tests\Unit\Services\Company\Employee\ConsultantRate;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ConsultantRate;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\ConsultantRate\DestroyConsultantRate;

class DestroyConsultantRateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_the_consultant_rate_of_the_consultant_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'rate' => 100,
        ]);

        $this->executeService($michael, $dwight, $rate);
    }

    /** @test */
    public function it_destroys_the_consultant_rate_of_the_consultant_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'rate' => 100,
        ]);

        $this->executeService($michael, $dwight, $rate);
    }

    /** @test */
    public function it_destroys_the_consultant_rate_of_the_consultant_as_manager_of_the_person(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'rate' => 100,
        ]);

        $this->executeService($michael, $dwight, $rate);
    }

    /** @test */
    public function user_can_execute_the_service_in_his_own_profile(): void
    {
        $michael = $this->createEmployee();
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'rate' => 100,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $michael, $rate);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'rate' => 100,
        ]);

        $this->executeService($michael, $dwight, $rate);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyConsultantRate)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'rate' => 100,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $rate);
    }

    /** @test */
    public function it_fails_if_rate_doesnt_belong_to_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $rate = ConsultantRate::create([
            'company_id' => $michael->company_id,
            'rate' => 100,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $rate);
    }

    private function executeService(Employee $michael, Employee $consultant, ConsultantRate $rate): void
    {
        Queue::fake();

        Carbon::setTestNow(Carbon::create(2017, 1, 1));

        $request = [
            'company_id' => $consultant->company_id,
            'author_id' => $michael->id,
            'employee_id' => $consultant->id,
            'rate_id' => $rate->id,
        ];

        (new DestroyConsultantRate)->execute($request);

        $this->assertDatabaseMissing('consultant_rates', [
            'id' => $rate->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $consultant, $rate) {
            return $job->auditLog['action'] === 'consultant_rate_destroy' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $consultant->id,
                    'employee_name' => $consultant->name,
                    'rate' => $rate->rate,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $rate) {
            return $job->auditLog['action'] === 'consultant_rate_destroy' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'rate' => $rate->rate,
                ]);
        });
    }
}
