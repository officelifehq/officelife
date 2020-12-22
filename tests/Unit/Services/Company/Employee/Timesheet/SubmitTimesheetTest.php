<?php

namespace Tests\Unit\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\SubmitTimesheet;

class SubmitTimesheetTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_submits_a_timesheet_as_administrator(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $timesheet);
    }

    /** @test */
    public function it_submits_a_timesheet_as_hr(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $timesheet);
    }

    /** @test */
    public function it_submits_a_timesheet_as_normal_user(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createEmployee();
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);
        $this->executeService($michael, $michael, $timesheet);
    }

    /** @test */
    public function normal_user_can_create_a_timesheet_on_behalf_of_another_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, $timesheet);
    }

    /** @test */
    public function it_fails_if_employee_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $jim = factory(Employee::class)->create([]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $jim->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jim, $timesheet);
    }

    /** @test */
    public function it_fails_if_timesheet_is_not_part_of_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $jim = factory(Employee::class)->create([]);
        $timesheet = Timesheet::factory()->create([
            'employee_id' => $jim->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jim, $timesheet);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new SubmitTimesheet)->execute($request);
    }

    private function executeService(Employee $author, Employee $employee, Timesheet $timesheet): void
    {
        Queue::fake();

        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'employee_id' => $employee->id,
            'timesheet_id' => $timesheet->id,
        ];

        $timesheet = (new SubmitTimesheet)->execute($request);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'status' => Timesheet::READY_TO_SUBMIT,
            'started_at' => Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00'),
            'ended_at' => Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59'),
        ]);

        $this->assertInstanceOf(
            Timesheet::class,
            $timesheet
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $employee, $timesheet) {
            return $job->auditLog['action'] === 'timesheet_submitted' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $employee->id,
                    'timesheet_id' => $timesheet->id,
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($author, $timesheet) {
            return $job->auditLog['action'] === 'timesheet_submitted' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'timesheet_id' => $timesheet->id,
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                ]);
        });
    }
}
