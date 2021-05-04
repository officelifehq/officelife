<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeePositionHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company(): void
    {
        $position = Position::factory()->create([]);
        $this->assertTrue($position->company()->exists());
    }

    /** @test */
    public function it_belongs_to_many_employees(): void
    {
        $position = Position::factory()->create([]);
        Employee::factory(3)->create([
            'company_id' => $position->company_id,
            'position_id' => $position->id,
        ]);

        $this->assertTrue($position->employees()->exists());
    }

    /** @test */
    public function it_has_many_employee_position_history_entries(): void
    {
        $position = Position::factory()->create([]);
        EmployeePositionHistory::factory(2)->create([
            'position_id' => $position->id,
        ]);

        $this->assertTrue($position->positionHistoryEntries()->exists());
    }
}
