<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneEntry;

class DestroyOneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_an_entry_as_administrator(): void
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
    public function it_deletes_an_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $entry);
    }

    /** @test */
    public function normal_user_can_execute_the_service(): void
    {
        $michael = $this->createEmployee();
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
        (new DestroyOneOnOneEntry)->execute($request);
    }

    /** @test */
    public function it_fails_if_author_is_not_either_the_manager_or_the_employee(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createEmployee();
        $john = $this->createEmployee();
        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($john, $entry);
    }

    private function executeService(Employee $michael, OneOnOneEntry $entry): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'one_on_one_entry_id' => $entry->id,
        ];

        (new DestroyOneOnOneEntry)->execute($request);

        $this->assertDatabaseMissing('one_on_one_entries', [
            'id' => $entry->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->name,
                    'manager_id' => $entry->manager->id,
                    'manager_name' => $entry->manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['employee_id'] === $entry->employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->manager->id,
                    'employee_name' => $entry->manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['employee_id'] === $entry->manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'happened_at' => $entry->happened_at->format('Y-m-d'),
                    'employee_id' => $entry->employee->id,
                    'employee_name' => $entry->employee->name,
                ]);
        });
    }
}
