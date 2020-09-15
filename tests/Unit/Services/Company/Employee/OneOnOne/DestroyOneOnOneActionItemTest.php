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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneActionItem;

class DestroyOneOnOneActionItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_an_action_item_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $actionItem = factory(OneOnOneActionItem::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $actionItem);
    }

    /** @test */
    public function it_deletes_an_action_item_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $actionItem = factory(OneOnOneActionItem::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $actionItem);
    }

    /** @test */
    public function normal_user_can_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $actionItem = factory(OneOnOneActionItem::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $actionItem);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyOneOnOneActionItem)->execute($request);
    }

    private function executeService(Employee $michael, OneOnOneEntry $entry, OneOnOneActionItem $actionItem): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'one_on_one_entry_id' => $entry->id,
            'one_on_one_action_item_id' => $actionItem->id,
        ];

        (new DestroyOneOnOneActionItem)->execute($request);

        $this->assertDatabaseMissing('one_on_one_action_items', [
            'id' => $actionItem->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_action_item_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->name,
                    'manager_id' => $entry->manager->id,
                    'manager_name' => $entry->manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_action_item_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['employee_id'] === $entry->employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->manager->id,
                    'employee_name' => $entry->manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_action_item_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['employee_id'] === $entry->manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->name,
                ]);
        });
    }
}
