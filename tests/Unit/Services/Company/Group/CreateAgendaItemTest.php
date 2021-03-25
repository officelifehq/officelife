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
use App\Services\Company\Group\CreateAgendaItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateAgendaItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_agenda_item_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    /** @test */
    public function it_creates_an_agenda_item_as_administrator_and_assigns_a_presenter_who_is_not_part_of_the_group(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);

        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    /** @test */
    public function it_creates_an_agenda_item_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    /** @test */
    public function it_creates_an_agenda_item_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateAgendaItem)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_group_doesnt_belong_to_the_company(): void
    {
        $michael = Employee::factory()->create([]);
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create();
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    /** @test */
    public function it_fails_if_the_meeting_doesnt_belong_to_the_group(): void
    {
        $michael = Employee::factory()->create([]);
        $dwight = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create();
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
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
        $meeting->employees()->syncWithoutDetaching([
            $dwight->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $michael->company, $group, $meeting);
    }

    private function executeService(Employee $michael, Employee $presenter, Company $company, Group $group, Meeting $meeting): void
    {
        Queue::fake();

        $request = [
            'company_id' => $company->id,
            'author_id' => $michael->id,
            'group_id' => $group->id,
            'meeting_id' => $meeting->id,
            'summary' => 'Super agenda item',
            'description' => null,
            'presented_by_id' => $presenter->id,
        ];

        $agendaItem = (new CreateAgendaItem)->execute($request);

        $this->assertDatabaseHas('agenda_items', [
            'id' => $agendaItem->id,
            'meeting_id' => $meeting->id,
            'summary' => 'Super agenda item',
            'presented_by_id' => $presenter->id,
        ]);

        $this->assertInstanceOf(
            AgendaItem::class,
            $agendaItem
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $group, $meeting) {
            return $job->auditLog['action'] === 'agenda_item_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'meeting_id' => $meeting->id,
                ]);
        });
    }
}
