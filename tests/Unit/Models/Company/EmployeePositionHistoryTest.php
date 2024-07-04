<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\EmployeePositionHistory;
use Tests\TestCase;

class EmployeePositionHistoryTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $positionHistory = EmployeePositionHistory::factory()->create([]);
        $this->assertTrue($positionHistory->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_a_position(): void
    {
        $positionHistory = EmployeePositionHistory::factory()->create([]);

        $this->assertTrue($positionHistory->position()->exists());
    }
}
