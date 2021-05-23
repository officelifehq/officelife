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
use App\Services\Company\Group\AddGuestToMeeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddGuestToMeetingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_employee_to_a_meeting_as_guest_as_administrator(): void
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
    public function it_adds_an_employee_to_a_meeting_as_guest_as_hr(): void
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
    public function it_adds_an_employee_to_a_meeting_as_guest_as_normal_user(): void
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
        (new AddGuestToMeeting)->execute($request);
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

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ];

        $employee = (new AddGuestToMeeting)->execute($request);

        $this->assertDatabaseHas('employee_meeting', [
            'meeting_id' => $meeting->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $dwight, $meeting) {
            return $job->auditLog['action'] === 'add_guest_to_meeting' &&
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
            return $job->auditLog['action'] === 'add_guest_to_meeting' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                'meeting_id' => $meeting->id,
                ]);
        });
    }
}
