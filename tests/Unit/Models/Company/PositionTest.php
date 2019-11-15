<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company(): void
    {
        $position = factory(Position::class)->create([]);
        $this->assertTrue($position->company()->exists());
    }

    /** @test */
    public function it_belongs_to_many_employees(): void
    {
        $position = factory(Position::class)->create([]);
        factory(Employee::class, 3)->create([
            'company_id' => $position->company_id,
            'position_id' => $position->id,
        ]);

        $this->assertTrue($position->employees()->exists());
    }
}
