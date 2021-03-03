<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Group\CreateGroup;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProject;
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
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateProject)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Steering Commitee',
        ];

        $group = (new CreateGroup)->execute($request);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
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
    }
}
