<?php

namespace Tests\Unit\Services\Company\Employee\Manager;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Manager\UnassignManager;

class UnassignManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_unassigns_a_manager_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_unassigns_a_manager_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new UnassignManager)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_and_manager_are_not_in_the_same_account(): void
    {
        $company = factory(Company::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $manager = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);

        factory(DirectReport::class)->create([
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UnassignManager)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $dwight = factory(DirectReport::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $dwight->directReport->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->directReport->id,
            'manager_id' => $dwight->manager->id,
        ];

        $manager = (new UnassignManager)->execute($request);

        $this->assertDatabaseMissing('direct_reports', [
            'company_id' => $dwight->directReport->company_id,
            'employee_id' => $dwight->directReport->id,
            'manager_id' => $dwight->manager->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $manager
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($dwight, $michael) {
            return $job->auditLog['action'] === 'manager_unassigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $dwight->manager->id,
                    'manager_name' => $dwight->manager->name,
                    'employee_id' => $dwight->directReport->id,
                    'employee_name' => $dwight->directReport->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $michael) {
            return $job->auditLog['action'] === 'manager_unassigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $dwight->manager->id,
                    'manager_name' => $dwight->manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $michael) {
            return $job->auditLog['action'] === 'direct_report_unassigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'direct_report_id' => $dwight->directReport->id,
                    'direct_report_name' => $dwight->directReport->name,
                ]);
        });
    }
}
