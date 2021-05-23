<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\EmployeePositionHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeePositionHistoryTest extends TestCase
{
    use DatabaseTransactions;

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
