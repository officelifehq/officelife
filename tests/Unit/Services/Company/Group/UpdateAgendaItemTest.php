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
use Illuminate\Validation\ValidationException;
use App\Services\Company\Group\UpdateAgendaItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateAgendaItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_an_agenda_item_as_administrator(): void
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
    public function it_updates_an_agenda_item_as_administrator_and_assigns_a_presenter_who_is_not_part_of_the_group(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem, $dwight);
    }

    /** @test */
    public function it_updates_an_agenda_item_as_hr(): void
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
    public function it_updates_an_agenda_item_as_normal_user(): void
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
        (new UpdateAgendaItem)->execute($request);
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
    public function it_fails_if_the_agenda_item_does_not_belong_to_the_meeting(): void
    {
        $michael = Employee::factory()->create([]);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create();
        $agendaItem = AgendaItem::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem);
    }

    /** @test */
    public function it_fails_if_the_presenter_is_not_in_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $dwight = Employee::factory()->create([]);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $agendaItem = AgendaItem::factory()->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael->company, $group, $meeting, $agendaItem, $dwight);
    }

    private function executeService(Employee $michael, Company $company, Group $group, Meeting $meeting, AgendaItem $agendaItem, Employee $presenter = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $company->id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'agenda_item_id' => $agendaItem->id,
            'summary' => 'Super agenda item',
            'description' => null,
            'presented_by_id' => $presenter ? $presenter->id : null,
        ];

        $agendaItem = (new UpdateAgendaItem)->execute($request);

        $this->assertDatabaseHas('agenda_items', [
            'id' => $agendaItem->id,
            'meeting_id' => $meeting->id,
            'summary' => 'Super agenda item',
            'presented_by_id' => $presenter ? $presenter->id : null,
        ]);

        $this->assertInstanceOf(
            AgendaItem::class,
            $agendaItem
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'agenda_item_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
