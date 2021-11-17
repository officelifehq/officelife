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
use App\Services\Company\Employee\DisciplineCase\CreateDisciplineEvent;

class CreateDisciplineEventTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_discipline_event_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $case);
    }

    /** @test */
    public function it_creates_a_discipline_event_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $case);
    }

    /** @test */
    public function it_creates_a_discipline_event_as_manager(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
        ]);
        $this->executeService($michael, $dwight, $case);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, $case);
    }

    /** @test */
    public function it_fails_if_discipline_case_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $case = DisciplineCase::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $case);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateDisciplineEvent)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight, DisciplineCase $case): void
    {
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'discipline_case_id' => $case->id,
            'happened_at' => '2010-10-10',
            'description' => 'this is a description',
        ];

        $event = (new CreateDisciplineEvent)->execute($request);

        $this->assertDatabaseHas('discipline_events', [
            'id' => $event->id,
            'discipline_case_id' => $case->id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'happened_at' => '2010-10-10 00:00:00',
            'description' => 'this is a description',
        ]);

        $this->assertInstanceOf(
            DisciplineEvent::class,
            $event
        );
    }
}
