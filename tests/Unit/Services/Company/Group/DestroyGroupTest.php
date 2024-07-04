<?php

namespace Tests\Unit\Services\Company\Group;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Group;
use App\Services\Company\Group\DestroyGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyGroupTest extends TestCase
{
    /** @test */
    public function it_destroys_a_group_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_destroys_a_group_as_hr(): void
    {
        $michael = $this->createHR();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_destroys_a_group_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_fails_if_project_is_not_part_of_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyGroup)->execute($request);
    }

    private function executeService(Employee $michael, ?Group $group = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
        ];

        (new DestroyGroup)->execute($request);

        $this->assertDatabaseMissing('groups', [
            'id' => $group->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group) {
            return $job->auditLog['action'] === 'group_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_name' => $group->name,
                ]);
        });
    }
}
