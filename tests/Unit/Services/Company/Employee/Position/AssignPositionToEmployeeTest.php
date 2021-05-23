<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Models\Company\EmployeePositionHistory;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;

class AssignPositionToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_position_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_assigns_a_position_as_administrator_and_updates_the_position_history(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->executeService($michael, $dwight, true);
    }

    /** @test */
    public function it_assigns_a_position_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_the_position_does_not_exist_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $position = Position::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'position_id' => $position->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new AssignPositionToEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignPositionToEmployee)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight, bool $hasPreviousEmployeeHistoryEntry = false): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $position = Position::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'position_id' => $position->id,
        ];

        if ($hasPreviousEmployeeHistoryEntry) {
            EmployeePositionHistory::factory()->create([
                'employee_id' => $dwight->id,
                'position_id' => $position->id,
                'started_at' => '1900-01-01 00:00:00',
            ]);
        }

        $dwight = (new AssignPositionToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $dwight->company_id,
            'id' => $dwight->id,
            'position_id' => $position->id,
        ]);

        $this->assertDatabaseHas('employee_position_history', [
            'employee_id' => $dwight->id,
            'position_id' => $position->id,
            'started_at' => Carbon::now(),
        ]);

        if ($hasPreviousEmployeeHistoryEntry) {
            $this->assertDatabaseHas('employee_position_history', [
                'employee_id' => $dwight->id,
                'position_id' => $position->id,
                'started_at' => '1900-01-01 00:00:00',
                'ended_at' => Carbon::now(),
            ]);
        }

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $position) {
            return $job->auditLog['action'] === 'position_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'position_id' => $position->id,
                    'position_title' => $position->title,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'position_id' => $position->id,
                    'position_title' => $position->title,
                ]);
        });
    }
}
