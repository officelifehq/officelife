<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FlowTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $flow = Flow::factory()->create([]);
        $this->assertTrue($flow->company()->exists());
    }

    /** @test */
    public function it_has_many_steps(): void
    {
        $flow = Flow::factory()->create();
        Step::factory()->count(2)->create([
            'flow_id' => $flow->id,
        ]);

        $this->assertTrue($flow->steps()->exists());
    }
}
