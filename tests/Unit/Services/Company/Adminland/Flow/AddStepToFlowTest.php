<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Flow\AddStepToFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddStepToFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_step_to_a_flow(): void
    {
        $employee = factory(Employee::class)->create([]);
        $flow = factory(Flow::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $employee->id,
            'flow_id' => $flow->id,
            'number' => 6,
            'unit_of_time' => 'days',
            'modifier' => 'before',
        ];

        $step = (new AddStepToFlow)->execute($request);

        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'flow_id' => $flow->id,
            'number' => 6,
            'unit_of_time' => 'days',
            'real_number_of_days' => -6,
        ]);

        $this->assertInstanceOf(
            Step::class,
            $step
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $employee = factory(Employee::class)->create([]);
        $flow = factory(Flow::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $employee->user_id,
            'flow_id' => $flow->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddStepToFlow)->execute($request);
    }
}
