<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisciplineCaseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $case = DisciplineCase::factory()->create();
        $this->assertTrue($case->company()->exists());
    }

    /** @test */
    public function it_is_about_one_employee(): void
    {
        $dwight = Employee::factory()->create();
        $case = DisciplineCase::factory()->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($case->employee()->exists());
    }

    /** @test */
    public function it_is_by_one_employee(): void
    {
        $dwight = Employee::factory()->create();
        $case = DisciplineCase::factory()->create([
            'company_id' => $dwight->company_id,
            'opened_by_employee_id' => $dwight->id,
        ]);

        $this->assertTrue($case->author()->exists());
    }

    /** @test */
    public function it_has_many_events(): void
    {
        $case = DisciplineCase::factory()->create();
        DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
        ]);

        $this->assertTrue($case->events()->exists());
    }
}
