<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Flow\RemoveStepFromFlow;

class RemoveStepFromFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_step_from_a_flow(): void
    {
        $employee = factory(Employee::class)->create([]);
        $flow = factory(Flow::class)->create([
            'company_id' => $employee->company_id,
        ]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'flow_id' => $flow->id,
            'step_id' => $step->id,
        ];

        $flow = (new RemoveStepFromFlow)->execute($request);

        $this->assertDatabaseMissing('steps', [
            'id' => $step->id,
            'flow_id' => $step->flow_id,
        ]);

        $this->assertInstanceOf(
            Flow::class,
            $flow
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveStepFromFlow)->execute($request);
    }
}
