<?php

namespace Tests\Unit\Services\Company\Adminland\EmployeeStatus;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\EmployeeStatus\DestroyEmployeeStatus;

class DestroyEmployeeStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_employee_status() : void
    {
        Queue::fake();

        $employeeStatus = factory(EmployeeStatus::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $employeeStatus->company_id,
        ]);

        $request = [
            'company_id' => $employeeStatus->company_id,
            'author_id' => $michael->user->id,
            'employee_status_id' => $employeeStatus->id,
        ];

        (new DestroyEmployeeStatus)->execute($request);

        $this->assertDatabaseMissing('employee_statuses', [
            'id' => $employeeStatus->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeStatus) {
            return $job->auditLog['action'] === 'employee_status_destroyed' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_status_name' => $employeeStatus->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyEmployeeStatus)->execute($request);
    }
}
