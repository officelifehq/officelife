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

class ScheduleFlowsForEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_schedules_all_the_flows_for_an_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::DATE_BASED,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
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

        $this->assertDatabaseHas('scheduled_actions', [
            'action_id' => $action->id,
            'employee_id' => $michael->id,
            'triggered_at' => '2021-03-11 00:00:00',
            'content' => $action->content,
        ]);
    }

    /** @test */
    public function it_doesnt_schedule_a_flow_as_none_are_about_this_trigger_type(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01',
        ]);
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'type' => Flow::EVENT_BASED,
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
            'content' => $action->content,
        ]);
    }
}
