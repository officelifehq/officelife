<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\EmployeePositionHistory;
use App\Models\Company\Position;
use Tests\TestCase;

class PositionTest extends TestCase
{
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
