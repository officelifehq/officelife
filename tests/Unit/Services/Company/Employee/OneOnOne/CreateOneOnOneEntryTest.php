<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Exceptions\SameIdsException;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\OneOnOneActionItem;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneEntry;

class CreateOneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_entry_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_an_entry_and_migrate_previous_unchecked_action_items(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);

        $oldEntry = OneOnOneEntry::create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'happened_at' => '2000-02-02',
        ]);

        OneOnOneActionItem::factory()->count(2)->create([
            'one_on_one_entry_id' => $oldEntry->id,
            'checked' => true,
        ]);
        OneOnOneActionItem::factory()->count(2)->create([
            'one_on_one_entry_id' => $oldEntry->id,
            'checked' => false,
        ]);

        $this->executeService($michael, $dwight, $oldEntry);
    }

    /** @test */
    public function it_creates_an_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createDirectReport($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_can_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user_who_is_not_a_manager(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createEmployee();
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateOneOnOneEntry)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_and_manager_are_the_same_person(): void
    {
        $this->expectException(SameIdsException::class);
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_company(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $michael = $this->createAdministrator();
        $dwight = $this->createAdministrator();
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_the_manager_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);

        $company = Company::factory()->create([]);
        $michael->company_id = $company->id;
        $michael->save();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    private function executeService(Employee $manager, Employee $employee, OneOnOneEntry $oldEntry = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $manager->company_id,
            'author_id' => $manager->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
            'date' => '2020-10-10',
        ];

        $entry = (new CreateOneOnOneEntry)->execute($request);

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $entry->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);

        $this->assertInstanceOf(
            OneOnOneEntry::class,
            $entry
        );

        if ($oldEntry) {
            $this->assertEquals(
                2,
                $entry->talkingPoints->count()
            );
        }

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($manager, $employee, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_created' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $employee, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_created' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['employee_id'] === $employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'employee_id' => $manager->id,
                    'employee_name' => $manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $employee, $entry) {
            return $job->auditLog['action'] === 'one_on_one_entry_created' &&
                $job->auditLog['author_id'] === $manager->id &&
                $job->auditLog['employee_id'] === $manager->id &&
                $job->auditLog['objects'] === json_encode([
                    'one_on_one_entry_id' => $entry->id,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                ]);
        });
    }
}
