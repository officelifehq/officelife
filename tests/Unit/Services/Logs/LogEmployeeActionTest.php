<?php

namespace Tests\Unit\Services\Logs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Services\Logs\LogEmployeeAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogEmployeeActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogEmployeeAction)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_author_is_not_in_the_same_company_as_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        $date = Carbon::now();

        $request = [
            'employee_id' => $dwight->id,
            'action' => 'account_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => '{"user": 1}',
        ];

        $employeeLog = (new LogEmployeeAction)->execute($request);

        $this->assertDatabaseHas('employee_logs', [
            'id' => $employeeLog->id,
            'employee_id' => $dwight->id,
            'action' => 'account_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => '{"user": 1}',
        ]);

        $this->assertInstanceOf(
            EmployeeLog::class,
            $employeeLog
        );
    }
}
