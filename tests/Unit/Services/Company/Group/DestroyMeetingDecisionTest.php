<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use App\Models\Company\AgendaItem;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\MeetingDecision;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\DestroyMeetingDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyMeetingDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_meeting_decision_as_administrator(): void
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
        $meetingDecision = MeetingDecision::factory()->create([
            'agenda_item_id' => $agendaItem->id,
        ]);
        $this->executeService($michael, $group, $meeting, $agendaItem, $meetingDecision);
    }

    /** @test */
    public function it_destroys_a_meeting_decision_as_hr(): void
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
        $meetingDecision = MeetingDecision::factory()->create([
            'agenda_item_id' => $agendaItem->id,
        ]);
        $this->executeService($michael, $group, $meeting, $agendaItem, $meetingDecision);
    }

    /** @test */
    public function it_destroys_a_meeting_decision_as_normal_user(): void
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
        $meetingDecision = MeetingDecision::factory()->create([
            'agenda_item_id' => $agendaItem->id,
        ]);
        $this->executeService($michael, $group, $meeting, $agendaItem, $meetingDecision);
    }

    /** @test */
    public function it_fails_if_meeting_is_not_part_of_the_group(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);
        $meetingDecision = MeetingDecision::factory()->create([
            'agenda_item_id' => $agendaItem->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group, $meeting, $agendaItem, $meetingDecision);
    }

    /** @test */
    public function it_fails_if_agenda_item_is_not_part_of_the_meeting(): void
    {
        $michael = $this->createEmployee();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create();
        $meetingDecision = MeetingDecision::factory()->create([
            'agenda_item_id' => $agendaItem->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group, $meeting, $agendaItem, $meetingDecision);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyMeetingDecision)->execute($request);
    }

    private function executeService(Employee $michael, Group $group, Meeting $meeting, AgendaItem $agendaItem, MeetingDecision $meetingDecision): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'agenda_item_id' => $agendaItem->id,
            'meeting_decision_id' => $meetingDecision->id,
        ];

        (new DestroyMeetingDecision)->execute($request);

        $this->assertDatabaseMissing('meeting_decisions', [
            'id' => $meetingDecision->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group) {
            return $job->auditLog['action'] === 'meeting_decision_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_name' => $group->name,
                ]);
        });
    }
}
