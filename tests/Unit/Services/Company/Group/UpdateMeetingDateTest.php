<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\UpdateMeetingDate;
use App\Services\Company\Group\RemoveGuestFromMeeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateMeetingDateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_date_of_a_meeting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $group, $meeting);
    }

    /** @test */
    public function it_updates_the_date_of_a_meeting_as_hr(): void
    {
        $michael = $this->createHR();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $group, $meeting);
    }

    /** @test */
    public function it_updates_the_date_of_a_meeting_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $group, $meeting);
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
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group, $meeting);
    }

    private function executeService(Employee $michael, Group $group, Meeting $meeting): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'date' => '2020-10-30',
        ];

        (new UpdateMeetingDate)->execute($request);

        $this->assertDatabaseHas('meetings', [
            'id' => $meeting->id,
            'happened_at' => '2020-10-30 00:00:00',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'meeting_date_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
