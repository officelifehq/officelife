<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\RemoveGuestFromMeeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RemoveGuestFromMeetingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_from_a_meeting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $dwight, $group, $meeting);
    }

    /** @test */
    public function it_removes_an_employee_from_a_meeting_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $dwight, $group, $meeting);
    }

    /** @test */
    public function it_removes_an_employee_from_a_meeting_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $this->executeService($michael, $michael, $group, $meeting);
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

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group, $meeting);
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

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $group, $meeting);
    }

    private function executeService(Employee $michael, Employee $dwight, Group $group, Meeting $meeting): void
    {
        Queue::fake();

        DB::table('employee_meeting')->insert([
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ];

        (new RemoveGuestFromMeeting)->execute($request);

        $this->assertDatabaseMissing('employee_meeting', [
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $dwight, $meeting) {
            return $job->auditLog['action'] === 'employee_removed_from_meeting' &&
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
            return $job->auditLog['action'] === 'employee_removed_from_meeting' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                'meeting_id' => $meeting->id,
                ]);
        });
    }
}
