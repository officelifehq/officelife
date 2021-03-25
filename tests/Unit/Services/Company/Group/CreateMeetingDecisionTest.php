<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use App\Models\Company\AgendaItem;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\MeetingDecision;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\CreateMeetingDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateMeetingDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_meeting_decision_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_creates_a_meeting_decision_as_hr(): void
    {
        $michael = $this->createHR();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_creates_a_meeting_decision_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateMeetingDecision)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_group_doesnt_belong_to_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create();
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_fails_if_the_meeting_doesnt_belong_to_the_group(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create();
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_fails_if_the_agenda_item_doesnt_belong_to_the_meeting(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    private function executeService(Employee $michael, Company $company, Group $group, Meeting $meeting, AgendaItem $agendaItem): void
    {
        Queue::fake();

        $request = [
            'company_id' => $company->id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'agenda_item_id' => $agendaItem->id,
            'description' => 'Description',
        ];

        $meetingDecision = (new CreateMeetingDecision)->execute($request);

        $this->assertDatabaseHas('meeting_decisions', [
            'id' => $meetingDecision->id,
            'agenda_item_id' => $agendaItem->id,
            'description' => 'Description',
        ]);

        $this->assertInstanceOf(
            MeetingDecision::class,
            $meetingDecision
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'meeting_decision_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
