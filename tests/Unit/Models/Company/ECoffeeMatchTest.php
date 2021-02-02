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
    public function it_has_many_employees(): void
    {
        $match = ECoffeeMatch::factory()->create([]);

        $this->assertTrue($match->employees()->exists());
    }
}
