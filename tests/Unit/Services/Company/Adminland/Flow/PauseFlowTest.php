<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ScheduledAction;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Flow\PauseFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PauseFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_pauses_a_flow_as_administrator(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $michael->hired_at = '1990-01-01 00:00:00';
        $michael->locked = false;
        $michael->save();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'type' => Flow::DATE_BASED,
            'is_triggered_on_anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);
        $oldScheduledAction = ScheduledAction::factory()->create([
            'action_id' => $action->id,
            'processed' => true,
            'triggered_at' => Carbon::now()->addDays(100),
        ]);
        $this->executeService($michael, $flow, $oldScheduledAction);
    }

    /** @test */
    public function it_pauses_a_flow_as_hr(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createHR();
        $michael->hired_at = '1990-01-01 00:00:00';
        $michael->locked = false;
        $michael->save();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'type' => Flow::DATE_BASED,
            'is_triggered_on_anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);
        $oldScheduledAction = ScheduledAction::factory()->create([
            'action_id' => $action->id,
            'processed' => true,
            'triggered_at' => Carbon::now()->addDays(100),
        ]);
        $this->executeService($michael, $flow, $oldScheduledAction);
    }

    /** @test */
    public function it_pauses_a_flow_as_normal_user(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createEmployee();
        $michael->hired_at = '1990-01-01 00:00:00';
        $michael->locked = false;
        $michael->save();
        $flow = Flow::factory()->create([
            'company_id' => $michael->company_id,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
            'type' => Flow::DATE_BASED,
            'is_triggered_on_anniversary' => true,
        ]);
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);
        $oldScheduledAction = ScheduledAction::factory()->create([
            'action_id' => $action->id,
            'processed' => true,
            'triggered_at' => Carbon::now()->addDays(100),
        ]);
        $this->executeService($michael, $flow, $oldScheduledAction);
    }

    /** @test */
    public function it_fails_if_the_flow_is_not_part_of_the_employee_company(): void
    {
        $michael = $this->createEmployee();
        $flow = Flow::factory()->create();
        $step = Step::factory()->create([
            'flow_id' => $flow->id,
            'real_number_of_days' => 70,
        ]);
        $action = Action::factory()->create([
            'step_id' => $step->id,
        ]);
        $oldScheduledAction = ScheduledAction::factory()->create([
            'action_id' => $action->id,
            'processed' => true,
            'triggered_at' => Carbon::now()->addDays(100),
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $flow, $oldScheduledAction);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new PauseFlow)->execute($request);
    }

    private function executeService(Employee $michael, Flow $flow, ScheduledAction $oldScheduledAction): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'flow_id' => $flow->id,
        ];

        $flow = (new PauseFlow)->execute($request);

        $this->assertDatabaseHas('flows', [
            'id' => $flow->id,
            'paused' => true,
        ]);

        $this->assertDatabaseHas('scheduled_actions', [
            'id' => $oldScheduledAction->id,
            'processed' => true,
        ]);

        $this->assertEquals(
            0,
            ScheduledAction::where('processed', false)->count(),
        );

        $this->assertEquals(
            1,
            ScheduledAction::count(),
        );

        $this->assertInstanceOf(
            Flow::class,
            $flow
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $flow) {
            return $job->auditLog['action'] === 'flow_paused' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'flow_id' => $flow->id,
                    'flow_name' => $flow->name,
                ]);
        });
    }
}
