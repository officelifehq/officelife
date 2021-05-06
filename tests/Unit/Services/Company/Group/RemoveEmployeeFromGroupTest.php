<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\RemoveEmployeeFromGroup;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RemoveEmployeeFromGroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $group);
    }

    /** @test */
    public function it_removes_an_employee_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $group);
    }

    /** @test */
    public function it_removes_a_project_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$michael->id]);

        $this->executeService($michael, $michael, $group);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveEmployeeFromGroup)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group);
    }

    private function executeService(Employee $michael, Employee $dwight, Group $group): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'group_id' => $group->id,
        ];

        (new RemoveEmployeeFromGroup)->execute($request);

        $this->assertDatabaseMissing('employee_group', [
            'group_id' => $group->id,
            'employee_id' => $dwight->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $dwight) {
            return $job->auditLog['action'] === 'employee_removed_from_group' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $group) {
            return $job->auditLog['action'] === 'employee_removed_from_group' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                ]);
        });
    }
}
