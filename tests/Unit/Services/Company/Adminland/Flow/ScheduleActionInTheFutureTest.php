<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Flow\ScheduleFlowsForEmployee;
use App\Services\Company\Adminland\Flow\ScheduleActionInTheFuture;

class ScheduleActionInTheFutureTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_schedules_an_action_in_the_future_for_a_date_in_the_future(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);

        (new ScheduleActionInTheFuture)->execute($action, $michael);

        $this->assertDatabaseHas('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2020-03-11 00:00:00',
        ]);
    }

    /** @test */
    public function it_schedules_an_action_in_the_future_for_a_date_in_the_past(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '1900-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);

        (new ScheduleActionInTheFuture)->execute($action, $michael);

        $this->assertDatabaseHas('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2018-03-12 00:00:00',
        ]);
    }

    /** @test */
    public function it_doesnt_schedule_an_action_if_flow_is_not_based_on_an_anniversary_of_the_trigger_date(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2000-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'anniversary' => false,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);

        (new ScheduleFlowsForEmployee)->execute([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
        ]);

        $this->assertDatabaseMissing('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2021-03-11 00:00:00',
        ]);
    }
}
