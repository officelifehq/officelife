<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ConsultantRate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConsultantRateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $rate = ConsultantRate::factory()->create([]);
        $this->assertTrue($rate->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $rate = ConsultantRate::factory()->create([]);
        $this->assertTrue($rate->employee()->exists());
    }
}
