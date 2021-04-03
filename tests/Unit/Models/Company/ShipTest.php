<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShipTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $ship = Ship::factory()->create([]);
        $this->assertTrue($ship->team()->exists());
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $ship = Ship::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $ship->team->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $ship->team->company_id,
        ]);

        $ship->employees()->syncWithoutDetaching([$dwight->id]);
        $ship->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($ship->employees()->exists());
    }
}
