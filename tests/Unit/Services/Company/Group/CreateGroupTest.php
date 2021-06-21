<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\ServiceQueue;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Group\CreateGroup;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProject;
use App\Services\Company\Group\AddEmployeeToGroup;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateGroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_group_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_group_as_administrator_and_associate_employees(): void
    {
        $michael = $this->createAdministrator();
        $andrew = $this->createAnotherEmployee($michael);
        $john = $this->createAnotherEmployee($michael);

        $employees = [$andrew->id, $john->id];

        $this->executeService($michael, $employees);
    }

    /** @test */
    public function it_creates_a_group_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_group_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateProject)->execute($request);
    }

    private function executeService(Employee $michael, array $employees = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Steering Commitee',
            'employees' => $employees,
        ];

        $group = (new CreateGroup)->execute($request);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'company_id' => $group->company_id,
            'name' => 'Steering Commitee',
        ]);

        $this->assertInstanceOf(
            Group::class,
            $group
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group) {
            return $job->auditLog['action'] === 'group_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                ]);
        });

        if ($employees) {
            Queue::assertPushed(ServiceQueue::class, function ($service) {
                return $service instanceof ServiceQueue
                    && $service->service instanceof AddEmployeeToGroup;
            });
        }
    }
}
