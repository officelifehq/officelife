<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;

class CreateEmployeeStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_employee_status() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Permanent',
        ];

        $employeeStatus = (new CreateEmployeeStatus)->execute($request);

        $this->assertDatabaseHas('employee_statuses', [
            'id' => $employeeStatus->id,
            'company_id' => $michael->company_id,
            'name' => 'Permanent',
        ]);

        $this->assertInstanceOf(
            EmployeeStatus::class,
            $employeeStatus
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_status_id' => $employeeStatus->id,
                    'employee_status_name' => 'Permanent',
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Permanent',
        ];

        $this->expectException(ValidationException::class);
        (new CreateEmployeeStatus)->execute($request);
    }
}
