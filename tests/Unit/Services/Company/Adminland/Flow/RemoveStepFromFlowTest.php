<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Flow\RemoveStepFromFlow;

class RemoveStepFromFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_step_from_a_flow_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_removes_a_step_from_a_flow_as_hr(): void
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
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveStepFromFlow)->execute($request);
    }

    /** @test */
    public function it_fails_if_step_is_not_linked_to_flow(): void
    {
        $michael = $this->createAdministrator();

        $flow = factory(Flow::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $step = factory(Step::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'step_id' => $step->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        $flow = (new RemoveStepFromFlow)->execute($request);
    }

    /** @test */
    public function it_fails_if_flow_is_not_linked_to_company(): void
    {
        $michael = $this->createAdministrator();

        $flow = factory(Flow::class)->create([]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'step_id' => $step->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        $flow = (new RemoveStepFromFlow)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        $flow = factory(Flow::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $step = factory(Step::class)->create([
            'flow_id' => $flow->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
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
}
