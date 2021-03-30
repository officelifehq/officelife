<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Group\ToggleEmployeeParticipationInMeeting;

class ToggleEmployeeParticipationInMeetingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_a_participation_to_a_meeting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([$dwight->id]);
        $this->executeService($michael, $dwight, $group, $meeting, true);
        $this->executeService($michael, $dwight, $group, $meeting, false);
    }

    /** @test */
    public function it_toggles_a_participation_to_a_meeting_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([$dwight->id]);
        $this->executeService($michael, $dwight, $group, $meeting, true);
        $this->executeService($michael, $dwight, $group, $meeting, false);
    }

    /** @test */
    public function it_toggles_a_participation_to_a_meeting_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([$michael->id]);
        $this->executeService($michael, $michael, $group, $meeting, true);
        $this->executeService($michael, $michael, $group, $meeting, false);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new ToggleEmployeeParticipationInMeeting)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([$dwight->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group, $meeting, true);
    }

    /** @test */
    public function it_fails_if_the_group_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $group = Group::factory()->create();
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([$dwight->id]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group, $meeting, true);
    }

    private function executeService(Employee $michael, Employee $dwight, Group $group, Meeting $meeting, bool $attended): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ];

        $employee = (new ToggleEmployeeParticipationInMeeting)->execute($request);

        $this->assertDatabaseHas('employee_meeting', [
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
            'attended' => $attended,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $dwight, $meeting) {
            return $job->auditLog['action'] === 'employee_marked_as_participant_in_meeting' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'meeting_id' => $meeting->id,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'employee_marked_as_participant_in_meeting' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                'meeting_id' => $meeting->id,
                ]);
        });
    }
}
