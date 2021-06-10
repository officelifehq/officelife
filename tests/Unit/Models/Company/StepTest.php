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

    /** @test */
    public function it_calculates_the_real_number_of_days(): void
    {
        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_SAME_DAY,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            0,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_DAY,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -9,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_WEEK,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -63,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_MONTH,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -270,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_DAY,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            9,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_WEEK,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            63,
            $step->real_number_of_days
        );

        $step = Step::factory()->create([
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_MONTH,
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            270,
            $step->real_number_of_days
        );
    }
}
