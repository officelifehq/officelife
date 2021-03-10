<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\OneOnOneTalkingPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneOnOneTalkingPointTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $point = OneOnOneTalkingPoint::factory()->create([]);
        $this->assertTrue($point->entry()->exists());
    }
}
