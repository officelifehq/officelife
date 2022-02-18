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
            'unit_of_time' => Step::UNIT_DAY,
            'modifier' => Step::MODIFIER_BEFORE,
        ];

        $step = (new AddStepToFlow)->execute($request);

        $this->assertDatabaseHas('steps', [
            'id' => $step->id,
            'flow_id' => $flow->id,
            'number' => 6,
            'unit_of_time' => Step::UNIT_DAY,
            'real_number_of_days' => -6,
        ]);

        $this->assertInstanceOf(
            Step::class,
            $step
        );
    }

    /** @test */
    public function it_calculates_the_real_number_of_days(): void
    {
        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_SAME_DAY,
        ];
        $step = (new AddStepToFlow)->execute($request);
        $this->assertEquals(
            0,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_DAY,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);
        $this->assertEquals(
            -9,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_WEEK,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);

        $this->assertEquals(
            -63,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_BEFORE,
            'unit_of_time' => Step::UNIT_MONTH,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);

        $this->assertEquals(
            -270,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_DAY,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);

        $this->assertEquals(
            9,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_WEEK,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);

        $this->assertEquals(
            63,
            $step->real_number_of_days
        );

        $michael = $this->createAdministrator();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $request = [
            'company_id' => $flow->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
            'modifier' => Step::MODIFIER_AFTER,
            'unit_of_time' => Step::UNIT_MONTH,
            'number' => 9,
        ];
        $step = (new AddStepToFlow)->execute($request);

        $this->assertEquals(
            270,
            $step->real_number_of_days
        );
    }
}
