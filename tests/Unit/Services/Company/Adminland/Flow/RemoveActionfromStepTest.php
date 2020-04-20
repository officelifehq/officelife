<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Flow\RemoveActionFromStep;

class RemoveActionfromStepTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_action_from_a_step_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }
    /** @test */
    public function it_removes_an_action_from_a_step_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveActionFromStep)->execute($request);
    }

    /** @test */
    public function it_fails_if_action_is_not_linked_to_step(): void
    {
        $michael = $this->createAdministrator();

        $flow = factory(Flow::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);
        $action = factory(Action::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'step_id' => $step->id,
            'action_id' => $action->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new RemoveActionFromStep)->execute($request);
    }

    /** @test */
    public function it_fails_if_flow_is_not_linked_to_company(): void
    {
        $michael = $this->createAdministrator();

        $flow = factory(Flow::class)->create([]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);
        $action = factory(Action::class)->create([
            'step_id' => $step->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'step_id' => $step->id,
            'action_id' => $action->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new RemoveActionFromStep)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        $flow = factory(Flow::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);
        $action = factory(Action::class)->create([
            'step_id' => $step->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'step_id' => $step->id,
            'action_id' => $action->id,
        ];

        $step = (new RemoveActionFromStep)->execute($request);

        $this->assertDatabaseMissing('actions', [
            'id' => $action->id,
            'step_id' => $action->step_id,
        ]);

        $this->assertInstanceOf(
            Step::class,
            $step
        );
    }
}
