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
use App\Services\Company\Adminland\Flow\AddActionToStep;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddActionToStepTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_action_to_a_step_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_adds_an_action_to_a_step_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $employee = Employee::factory()->create();
        $flow = Flow::factory()->create([
            'company_id' => $employee->company_id,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'flow_id' => $step->flow_id,
            'specific_recipient_information' => json_encode(['team_id' => 1]),
        ];

        $this->expectException(ValidationException::class);
        (new AddActionToStep)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_action_doesnt_belong_to_a_step(): void
    {
        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $step = Step::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'step_id' => $step->id,
            'type' => Action::TYPE_CREATE_TASK,
            'content' => json_encode(['team_id' => 1]),
        ];

        $this->expectException(ModelNotFoundException::class);
        (new AddActionToStep)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
        ]);

        $json = json_encode(['team_id' => 1]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'flow_id' => $step->flow_id,
            'step_id' => $step->id,
            'type' => Action::TYPE_CREATE_TASK,
            'content' => $json,
        ];

        $action = (new AddActionToStep)->execute($request);

        $this->assertDatabaseHas('actions', [
            'id' => $action->id,
            'step_id' => $step->id,
            'type' => Action::TYPE_CREATE_TASK,
            'content' => $json,
        ]);

        $this->assertInstanceOf(
            Action::class,
            $action
        );
    }
}
