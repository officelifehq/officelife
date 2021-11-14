<?php

namespace Tests\Unit\Services\Company\Employee\DisciplineCase;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\DisciplineCase\DestroyDisciplineEvent;

class DestroyDisciplineEventTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_discipline_event_as_administrator(): void
    {
        $michael = Employee::factory()->asAdministrator()->create();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);
        $this->executeService($michael, $case, $event);
    }

    /** @test */
    public function it_destroys_a_discipline_event_as_hr(): void
    {
        $michael = Employee::factory()->asAdministrator()->create();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);
        $this->executeService($michael, $case, $event);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = Employee::factory()->create();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ]);
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $case, $event);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyDisciplineEvent)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_discipline_case_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create([]);
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $case, $event);
    }

    /** @test */
    public function it_fails_if_the_discipline_event_does_not_match_the_discipline_case(): void
    {
        $case = DisciplineCase::factory()->create([]);
        $michael = Employee::factory()->asNormalEmployee()->create();
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $case, $event);
    }

    private function executeService(Employee $michael, DisciplineCase $case, DisciplineEvent $event): void
    {
        $request = [
            'company_id' => $case->company_id,
            'author_id' => $michael->id,
            'discipline_case_id' => $case->id,
            'discipline_event_id' => $event->id,
        ];

        (new DestroyDisciplineEvent)->execute($request);

        $this->assertDatabaseMissing('discipline_events', [
            'id' => $event->id,
        ]);
    }
}
