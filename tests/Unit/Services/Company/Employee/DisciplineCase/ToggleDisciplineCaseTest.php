<?php

namespace Tests\Unit\Services\Company\Employee\DisciplineCase;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;
use App\Services\Company\Employee\DisciplineCase\ToggleDisciplineCase;

class ToggleDisciplineCaseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggle_the_discipline_case_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function it_toggle_the_discipline_case_as_hr(): void
    {
        $michael = $this->createHR();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateCompanyNews)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_discipline_case_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $case);
    }

    private function executeService(Employee $michael, DisciplineCase $case): void
    {
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'discipline_case_id' => $case->id,
        ];

        (new ToggleDisciplineCase)->execute($request);

        $this->assertDatabaseHas('discipline_cases', [
            'id' => $case->id,
            'active' => true,
        ]);
    }
}
