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
    public function it_belongs_to_a_flow()
    {
        $step = factory(Step::class)->create([]);
        $this->assertTrue($step->flow()->exists());
    }

    /** @test */
    public function it_has_many_actions()
    {
        $step = factory(Step::class)->create();
        factory(Action::class, 2)->create([
            'step_id' => $step->id,
        ]);

        $this->assertTrue($step->actions()->exists());
    }

    /** @test */
    public function it_calculates_the_real_number_of_days()
    {
        $step = factory(Step::class)->create([
            'modifier' => 'same_day',
        ]);
        $step->calculateDays();
        $this->assertEquals(
            0,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'before',
            'unit_of_time' => 'days',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -9,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'before',
            'unit_of_time' => 'weeks',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -63,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'before',
            'unit_of_time' => 'months',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            -270,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'after',
            'unit_of_time' => 'days',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            9,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'after',
            'unit_of_time' => 'weeks',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            63,
            $step->real_number_of_days
        );

        $step = factory(Step::class)->create([
            'modifier' => 'after',
            'unit_of_time' => 'months',
            'number' => 9,
        ]);
        $step->calculateDays();
        $this->assertEquals(
            270,
            $step->real_number_of_days
        );
    }
}
