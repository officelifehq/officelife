<?php

namespace Tests\Unit\Services\Company\Employee\Birthdate;

use Exception;
use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Birthdate\SetBirthdate;

class SetBirthdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_birthdate_of_the_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_sets_the_birthdate_of_the_employee_as_hr(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function user_can_execute_the_service_in_his_own_profile(): void
    {
        $michael = $this->createEmployee();
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
    public function it_throws_an_exception_if_the_date_is_not_valid(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'year' => 1978,
            'month' => 2,
            'day' => 31,
        ];

        $this->expectException(Exception::class);
        (new SetBirthdate)->execute($request);
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
        (new SetBirthdate)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $michael, Employee $employeeToUpdate): void
    {
        Queue::fake();

        Carbon::setTestNow(Carbon::create(2017, 1, 1));

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $employeeToUpdate->id,
            'year' => 1978,
            'month' => 10,
            'day' => 01,
        ];

        $employeeToUpdate = (new SetBirthdate)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $employeeToUpdate
        );

        $this->assertDatabaseHas('employees', [
            'id' => $employeeToUpdate->id,
            'birthdate' => '1978-10-01 00:00:00',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $employeeToUpdate) {
            return $job->auditLog['action'] === 'employee_birthday_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $employeeToUpdate->id,
                    'employee_name' => $employeeToUpdate->name,
                    'birthday' => '1978-10-01',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $employeeToUpdate) {
            return $job->auditLog['action'] === 'birthday_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'birthday' => '1978-10-01',
                ]);
        });
    }
}
