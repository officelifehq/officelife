<?php

namespace Tests\Unit\Services\Company\Group;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use App\Models\Company\AgendaItem;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Group\DestroyMeeting;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\DestroyAgendaItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyAgendaItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_agenda_item_as_administrator(): void
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
        $this->executeService($michael, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_destroys_an_agenda_item_as_hr(): void
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
        $this->executeService($michael, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_destroys_an_agenda_item_as_normal_user(): void
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
        $this->executeService($michael, $group, $meeting, $agendaItem);
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

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyMeeting)->execute($request);
    }

    private function executeService(Employee $michael, Group $group, Meeting $meeting, AgendaItem $agendaItem): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'agenda_item_id' => $agendaItem->id,
        ];

        (new DestroyAgendaItem)->execute($request);

        $this->assertDatabaseMissing('agenda_items', [
            'id' => $agendaItem->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'agenda_item_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
