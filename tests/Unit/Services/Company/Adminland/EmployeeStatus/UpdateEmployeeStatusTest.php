<?php

namespace Tests\Unit\Services\Company\Adminland\EmployeeStatus;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\EmployeeStatus\UpdateEmployeeStatus;

class UpdateEmployeeStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_an_employee_status_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_updates_an_employee_status_as_hr(): void
    {
        $this->executeService(config('officelife.permission_level.hr'));
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService(config('officelife.permission_level.user'));
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateEmployeeStatus)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_status_does_not_match_the_company(): void
    {
        $employeeStatus = EmployeeStatus::factory()->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $employeeStatus->company_id,
            'author_id' => $michael->id,
            'employee_status_id' => $employeeStatus->id,
            'name' => 'Non permanent',
            'type' => 'internal',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateEmployeeStatus)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $employeeStatus = EmployeeStatus::factory()->create([]);
        $michael = Employee::factory()->create([
            'company_id' => $employeeStatus->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $employeeStatus->company_id,
            'author_id' => $michael->id,
            'employee_status_id' => $employeeStatus->id,
            'name' => 'Non permanent',
            'type' => 'external',
        ];

        (new UpdateEmployeeStatus)->execute($request);

        $this->assertDatabaseHas('employee_statuses', [
            'id' => $employeeStatus->id,
            'company_id' => $employeeStatus->company_id,
            'name' => 'Non permanent',
            'type' => 'external',
        ]);

        $this->assertInstanceOf(
            EmployeeStatus::class,
            $employeeStatus
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_old_name' => $employeeStatus->name,
                    'employee_status_new_name' => 'Non permanent',
                ]);
        });
    }
}
