<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\MarkOneOnOneEntryAsHappened;

class MarkOneOnOneEntryAsHappenedTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_an_one_on_one_as_completed_as_administrator(): void
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
    public function it_marks_an_one_on_one_as_completed_as_hr(): void
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
        (new MarkOneOnOneEntryAsHappened)->execute($request);
    }

    private function executeService(Employee $michael, OneOnOneEntry $entry): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'one_on_one_entry_id' => $entry->id,
        ];

        $oldEntry = $entry;

        $entry = (new MarkOneOnOneEntryAsHappened)->execute($request);

        $this->assertInstanceOf(
            OneOnOneEntry::class,
            $entry
        );

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $oldEntry->id,
            'happened' => true,
        ]);

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $entry->id,
            'happened' => false,
        ]);
    }
}
