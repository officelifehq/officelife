<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ECoffeeMatchTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_e_coffee(): void
    {
        $match = ECoffeeMatch::factory()->create([]);
        $this->assertTrue($match->eCoffee()->exists());
    }

    /** @test */
    public function it_belongs_to_one_employee(): void
    {
        $match = ECoffeeMatch::factory()->create([]);

        $this->assertTrue($match->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_another_employee(): void
    {
        $match = ECoffeeMatch::factory()->create([]);

        $this->assertTrue($match->employeeMatchedWith()->exists());
    }
}
