<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\OneOnOneActionItem;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneActionItem;

class CreateOneOnOneActionItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_action_item_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $entry);
    }

    /** @test */
    public function it_creates_action_item_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createDirectReport($michael);
        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $entry);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateOneOnOneActionItem)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_author_is_not_the_manager_or_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $john = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($john, $entry);
    }

    private function executeService(Employee $manager, OneOnOneEntry $entry): void
    {
        Queue::fake();

        $request = [
            'company_id' => $manager->company_id,
            'author_id' => $manager->id,
            'one_on_one_entry_id' => $entry->id,
            'description' => 'we need to talk about love',
        ];

        $actionItem = (new CreateOneOnOneActionItem)->execute($request);

        $this->assertDatabaseHas('one_on_one_action_items', [
            'id' => $actionItem->id,
            'one_on_one_entry_id' => $entry->id,
        ]);

        $this->assertInstanceOf(
            OneOnOneActionItem::class,
            $actionItem
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($manager, $entry, $actionItem) {
            return $job->auditLog['action'] === 'one_on_one_action_item_created' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'one_on_one_action_item_id' => $actionItem->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->id,
                    'manager_id' => $entry->manager->id,
                    'manager_name' => $entry->manager->id,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $entry, $actionItem) {
            return $job->auditLog['action'] === 'one_on_one_action_item_created' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['employee_id'] === $entry->employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'one_on_one_action_item_id' => $actionItem->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->manager->id,
                    'employee_name' => $entry->manager->id,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $entry, $actionItem) {
            return $job->auditLog['action'] === 'one_on_one_action_item_created' &&
                $job->auditLog['author_id'] === $entry->manager->id &&
                $job->auditLog['employee_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'one_on_one_action_item_id' => $actionItem->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->id,
                ]);
        });
    }
}
