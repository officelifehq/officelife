<?php

namespace Tests\Unit\Services\Company\Employee\DisciplineCase;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\DisciplineCase\DestroyDisciplineCase;

class DestroyDisciplineCaseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_discipline_case_as_administrator(): void
    {
        $case = DisciplineCase::factory()->create([]);
        $michael = Employee::factory()->asAdministrator()->create([
            'company_id' => $case->company_id,
        ]);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function it_destroys_a_discipline_case_as_hr(): void
    {
        $case = DisciplineCase::factory()->create([]);
        $michael = Employee::factory()->asHR()->create([
            'company_id' => $case->company_id,
        ]);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $case = DisciplineCase::factory()->create([]);
        $michael = Employee::factory()->asNormalEmployee()->create([
            'company_id' => $case->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $case);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyDisciplineCase)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_discipline_case_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $case);
    }

    private function executeService(Employee $michael, DisciplineCase $case): void
    {
        $request = [
            'company_id' => $case->company_id,
            'author_id' => $michael->id,
            'discipline_case_id' => $case->id,
        ];

        (new DestroyDisciplineCase)->execute($request);

        $this->assertDatabaseMissing('discipline_cases', [
            'id' => $case->id,
        ]);
    }
}
