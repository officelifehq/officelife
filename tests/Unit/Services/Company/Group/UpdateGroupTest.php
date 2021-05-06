<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Group\UpdateGroup;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\RemoveGuestFromMeeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateGroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_details_of_the_group_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_updates_the_details_of_the_group_as_hr(): void
    {
        $michael = $this->createHR();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_updates_the_details_of_the_group_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $group);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveGuestFromMeeting)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_group_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group);
    }

    private function executeService(Employee $michael, Group $group): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'name' => 'title',
            'mission' => 'awesome mission',
        ];

        (new UpdateGroup)->execute($request);

        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'title',
            'mission' => 'awesome mission',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group) {
            return $job->auditLog['action'] === 'group_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => 'title',
                    'group_mission' => 'awesome mission',
                ]);
        });
    }
}
