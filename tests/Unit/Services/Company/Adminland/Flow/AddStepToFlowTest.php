<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Adminland\Flow\AddStepToFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddStepToFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_step_to_a_flow_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_adds_a_step_to_a_flow_as_hr(): void
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
        $employee = Employee::factory()->create();
        $flow = Flow::factory()->create([
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

    private function executeService(Employee $michael): void
    {
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
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
}
