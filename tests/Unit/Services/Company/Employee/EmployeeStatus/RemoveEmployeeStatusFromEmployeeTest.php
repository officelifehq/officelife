<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;
use App\Services\Company\Employee\EmployeeStatus\RemoveEmployeeStatusFromEmployee;

class RemoveEmployeeStatusFromEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_resets_an_employees_status(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $status = $michael->status;

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $michael = (new RemoveEmployeeStatusFromEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $michael->company_id,
            'id' => $michael->id,
            'employee_status_id' => null,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $status) {
            return $job->auditLog['action'] === 'employee_status_removed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'employee_status_id' => $status->id,
                    'employee_status_name' => $status->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $status) {
            return $job->auditLog['action'] === 'employee_status_removed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_status_id' => $status->id,
                    'employee_status_name' => $status->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemovePositionFromEmployee)->execute($request);
    }
}
