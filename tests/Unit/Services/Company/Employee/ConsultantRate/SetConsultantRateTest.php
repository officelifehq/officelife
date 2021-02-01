<?php

namespace Tests\Unit\Services\Company\Employee\Birthdate;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ConsultantRate;
use App\Models\Company\EmployeeStatus;
use App\Exceptions\NotConsultantException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\ConsultantRate\SetConsultantRate;

class SetConsultantRateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_consultant_rate_of_the_consultant_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight = $this->setEmployeeStatus($dwight, EmployeeStatus::EXTERNAL);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_the_consultant_rate_of_the_consultant_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight = $this->setEmployeeStatus($dwight, EmployeeStatus::EXTERNAL);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_the_consultant_rate_of_the_consultant_as_manager_of_the_person(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $dwight = $this->setEmployeeStatus($dwight, EmployeeStatus::EXTERNAL);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function user_can_execute_the_service_in_his_own_profile(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetConsultantRate)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $dwight = $this->setEmployeeStatus($dwight, EmployeeStatus::EXTERNAL);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_employee_does_not_have_an_employee_status_set_to_external(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight = $this->setEmployeeStatus($dwight, EmployeeStatus::INTERNAL);

        $this->expectException(NotConsultantException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_employee_does_not_have_an_employee_status_at_all(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotConsultantException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $consultant): void
    {
        Queue::fake();

        Carbon::setTestNow(Carbon::create(2017, 1, 1));

        // fill the DB with 2 other active rates
        ConsultantRate::create([
            'employee_id' => $consultant->id,
            'company_id' => $consultant->company_id,
            'active' => true,
            'rate' => 100,
        ]);

        $request = [
            'company_id' => $consultant->company_id,
            'author_id' => $michael->id,
            'employee_id' => $consultant->id,
            'rate' => 100,
        ];

        $rate = (new SetConsultantRate)->execute($request);

        $this->assertInstanceOf(
            ConsultantRate::class,
            $rate
        );

        $this->assertDatabaseHas('consultant_rates', [
            'id' => $rate->id,
            'employee_id' => $consultant->id,
            'company_id' => $consultant->company_id,
            'rate' => 100,
            'active' => true,
        ]);

        // make sure there is only one active consultant rate for the consultant
        $this->assertEquals(
            1,
            ConsultantRate::where('company_id', $consultant->company_id)
                ->where('employee_id', $consultant->id)
                ->where('active', true)
                ->count()
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $consultant) {
            return $job->auditLog['action'] === 'consultant_rate_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $consultant->id,
                    'employee_name' => $consultant->name,
                    'rate' => 100,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'consultant_rate_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'rate' => 100,
                ]);
        });
    }
}
