<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use App\Services\BaseServiceAction;
use App\Models\Company\ScheduledAction;
use App\Exceptions\MissingInformationInJsonAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BaseServiceActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_empty_keys_array(): void
    {
        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);

        $this->assertIsArray(
            $stub->keys()
        );
    }

    /** @test */
    public function it_raises_an_exception_if_a_key_is_missing_in_json(): void
    {
        $keys = [
            'product_name' => 'test',
        ];

        $scheduledAction = ScheduledAction::factory()->create([
            'content' => json_encode(['name' => 'test']),
        ]);

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class, [], '', true, true, true, ['keys']);

        $stub->expects($this->any())
             ->method('keys')
             ->will($this->returnValue($keys));

        $this->expectException(MissingInformationInJsonAction::class);
        $stub->validateJsonStructure($scheduledAction);
    }

    /** @test */
    public function it_marks_a_scheduled_action_as_processed(): void
    {
        $scheduledAction = ScheduledAction::factory()->create();

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);
        $stub->markAsProcessed($scheduledAction);

        $this->assertDatabaseHas('scheduled_actions', [
            'id' => $scheduledAction->id,
            'processed' => true,
        ]);
    }

    /** @test */
    public function it_schedules_a_new_iteration_of_the_action(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'is_triggered_on_anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);
        $stub->scheduleFutureIteration($action, $michael);

        $this->assertDatabaseHas('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2020-03-11 00:00:00',
        ]);
    }

    /** @test */
    public function it_doesnt_schedule_a_new_iteration_of_the_action_if_the_flow_is_not_an_anniversary(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'is_triggered_on_anniversary' => false,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);
        $stub->scheduleFutureIteration($action, $michael);

        $this->assertDatabaseMissing('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2021-03-11 00:00:00',
        ]);
    }
}
