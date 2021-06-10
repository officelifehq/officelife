<?php

namespace App\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Models\Company\Action;
use App\Models\Company\Employee;
use App\Models\Company\ScheduledAction;

class ScheduleActionInTheFuture
{
    private Carbon $date;

    /**
     * Schedule the future iteration of the given action.
     *
     * @param Action $action
     * @param Employee $employee
     */
    public function execute(Action $action, Employee $employee)
    {
        $step = $action->step;
        $flow = $action->step->flow;

        $this->determineTriggerDate($flow, $employee);
        $this->scheduleAction($step, $flow, $action, $employee);
    }

    private function determineTriggerDate(Flow $flow, Employee $employee): void
    {
        if ($flow->trigger == Flow::TRIGGER_HIRING_DATE) {
            $this->date = $employee->hired_at;
        }
    }

    private function scheduleAction(Step $step, Flow $flow, Action $action, Employee $employee): void
    {
        // real_number_of_days is either positive or negative, depending on the
        // step (a step can be before, or after a given date)
        if ($step->real_number_of_days >= 0) {
            $dateStepShouldBeTriggered = $this->date->copy()->addDays((int) $step->real_number_of_days);
        } else {
            $dateStepShouldBeTriggered = $this->date->copy()->subDays((int) $step->real_number_of_days * -1);
        }

        // if the flow is set for the anniversary of the given date, we
        // need to make sure that each step of the flow is executed in
        //the present or the future.
        // if it's not an anniversary, we simply don't process the step
        // in the past.
        if ($dateStepShouldBeTriggered->isPast() && ! $flow->anniversary) {
            return;
        }

        while ($dateStepShouldBeTriggered->isPast()) {
            $dateStepShouldBeTriggered->addYear();
        }

        ScheduledAction::create([
            'action_id' => $action->id,
            'employee_id' => $employee->id,
            'triggered_at' => $dateStepShouldBeTriggered,
            'content' => $action->content,
        ]);
    }
}
