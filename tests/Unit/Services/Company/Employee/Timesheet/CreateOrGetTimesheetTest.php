<?php

namespace Tests\Unit\Services\Company\Employee\Timesheet;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;

class CreateOrGetTimesheetTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_timesheet_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_timesheet_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_timesheet_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_can_create_a_timesheet_on_behalf_of_another_employee(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_employee_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $jim = factory(Employee::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jim);
    }

    /** @test */
    public function it_returns_an_existing_timesheet_if_the_timesheet_already_exists_for_this_time_period(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'started_at' => '2020-11-16 00:00:00',
            'ended_at' => '2020-11-22 23:59:59',
        ]);

        $this->executeService($michael, $dwight, $timesheet);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateOrGetTimesheet)->execute($request);
    }

    private function executeService(Employee $author, Employee $employee, Timesheet $existingTimesheet = null): void
    {
        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'employee_id' => $employee->id,
            'date' => '2020-11-17', // start date of week: 16 nov, end: 22 nov
        ];

        $timesheet = (new CreateOrGetTimesheet)->execute($request);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'status' => Timesheet::OPEN,
            'started_at' => '2020-11-16 00:00:00',
            'ended_at' => '2020-11-22 23:59:59',
        ]);

        if ($existingTimesheet) {
            $this->assertEquals(
                $timesheet->id,
                $existingTimesheet->id
            );
        }

        $this->assertInstanceOf(
            Timesheet::class,
            $timesheet
        );
    }
}
