<?php

namespace Tests\Unit\Services\Company\Employee\DisciplineCase;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\DisciplineCase\CreateDisciplineCase;

class CreateDisciplineCaseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_discipline_case_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_discipline_case_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_discipline_case_as_manager(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_employee_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateDisciplineCase)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $case = (new CreateDisciplineCase)->execute($request);

        $this->assertDatabaseHas('discipline_cases', [
            'id' => $case->id,
            'company_id' => $michael->company_id,
            'opened_by_employee_id' => $michael->id,
            'opened_by_employee_name' => $michael->name,
            'employee_id' => $dwight->id,
        ]);

        $this->assertInstanceOf(
            DisciplineCase::class,
            $case
        );
    }
}
