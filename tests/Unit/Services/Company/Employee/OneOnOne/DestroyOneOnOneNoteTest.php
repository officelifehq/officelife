<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneNote;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneNote;

class DestroyOneOnOneNoteTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_note_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $note = factory(OneOnOneNote::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $note);
    }

    /** @test */
    public function it_deletes_a_note_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $note = factory(OneOnOneNote::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $note);
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
        $note = factory(OneOnOneNote::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $note);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyOneOnOneNote)->execute($request);
    }

    private function executeService(Employee $michael, OneOnOneEntry $entry, OneOnOneNote $note): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'one_on_one_entry_id' => $entry->id,
            'one_on_one_note_id' => $note->id,
        ];

        (new DestroyOneOnOneNote)->execute($request);

        $this->assertDatabaseMissing('one_on_one_notes', [
            'id' => $note->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $entry) {
            return $job->auditLog['action'] === 'one_on_one_note_destroyed' &&
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
            return $job->auditLog['action'] === 'one_on_one_note_destroyed' &&
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
            return $job->auditLog['action'] === 'one_on_one_note_destroyed' &&
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
