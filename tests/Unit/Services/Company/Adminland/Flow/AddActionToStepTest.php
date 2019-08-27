<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Flow\AddActionToStep;

class AddActionToStepTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_action_to_a_step() : void
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
            'flow_id' => $step->flow_id,
            'step_id' => $step->id,
            'type' => 'notification',
            'recipient' => 'manager',
            'specific_recipient_information' => '{manager_id:1}',
        ];

        $action = (new AddActionToStep)->execute($request);

        $this->assertDatabaseHas('actions', [
            'id' => $action->id,
            'step_id' => $step->id,
            'type' => 'notification',
            'recipient' => 'manager',
            'specific_recipient_information' => '{manager_id:1}',
        ]);

        $this->assertInstanceOf(
            Action::class,
            $action
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
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
            'author_id' => $employee->user_id,
            'flow_id' => $step->flow_id,
            'specific_recipient_information' => '{manager_id:1}',
        ];

        $this->expectException(ValidationException::class);
        (new AddActionToStep)->execute($request);
    }
}
