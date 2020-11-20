<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\CreateTimeTrackingEntry;

class CreateTimeTrackingEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_time_tracking_entry_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_time_tracking_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_time_tracking_entry_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_create_a_timesheet_on_behalf_of_another_employee(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_time_tracking_entry_with_a_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $dwight, $project);
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
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $project);
    }

    /** @test */
    public function it_fails_if_total_duration_of_the_day_exceeds_24_hours(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateTimeTrackingEntry)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateTimeTrackingEntry)->execute($request);
    }

    private function executeService(Employee $author, Employee $employee, Project $project = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'employee_id' => $employee->id,
            'project_id' => $project ? $project->id : null,
            'duration' => 120,
            'date' => '2020-11-17', // week number 47 of the year
        ];

        $entry = (new CreateTimeTrackingEntry)->execute($request);

        $this->assertDatabaseHas('time_tracking_entries', [
            'id' => $entry->id,
            'employee_id' => $employee->id,
            'project_id' => $project ? $project->id : null,
            'duration' => 120,
            'happened_at' => '2020-11-17 00:00:00',
        ]);

        $this->assertInstanceOf(
            TimeTrackingEntry::class,
            $entry
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $employee) {
            return $job->auditLog['action'] === 'time_tracking_entry_created' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'week_number' => 47,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($author) {
            return $job->auditLog['action'] === 'time_tracking_entry_created' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'week_number' => 47,
                ]);
        });
    }
}
