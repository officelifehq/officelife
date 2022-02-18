<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Step;
use App\Models\Company\Action;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StepTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_flow(): void
    {
        $step = Step::factory()->create([]);
        $this->assertTrue($step->flow()->exists());
    }

    /** @test */
    public function it_has_many_actions(): void
    {
        $step = Step::factory()->create();
        Action::factory()->count(2)->create([
            'step_id' => $step->id,
        ]);

        $this->assertTrue($step->actions()->exists());
    }
}
