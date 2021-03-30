<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Group\CreateMeeting;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Project\CreateProject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateMeetingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_meeting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $michael->company, $group);
    }

    /** @test */
    public function it_creates_a_meeting_as_hr(): void
    {
        $michael = $this->createHR();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $michael->company, $group);
    }

    /** @test */
    public function it_creates_a_meeting_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $michael->company, $group);
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

    /** @test */
    public function it_fails_if_the_group_doesnt_belong_to_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group);
    }

    private function executeService(Employee $michael, Company $company, Group $group): void
    {
        Queue::fake();

        // add members in the group
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ]);
        $group->employees()->syncWithoutDetaching([$employee->id]);

        $request = [
            'company_id' => $company->id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
        ];

        $meeting = (new CreateMeeting)->execute($request);

        $this->assertDatabaseHas('meetings', [
            'id' => $meeting->id,
            'group_id' => $group->id,
        ]);

        $this->assertInstanceOf(
            Meeting::class,
            $meeting
        );

        $this->assertDatabaseHas('employee_meeting', [
            'meeting_id' => $meeting->id,
            'employee_id' => $employee->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'meeting_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
