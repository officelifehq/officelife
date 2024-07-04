<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ConsultantRate;
use Tests\TestCase;

class ConsultantRateTest extends TestCase
{
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
