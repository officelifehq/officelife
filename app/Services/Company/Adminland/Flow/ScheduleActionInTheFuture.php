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
    private Action $action;
    private Employee $employee;
    private Step $step;
    private Flow $flow;

    /**
     * Schedule the future iteration of the given action for the given employee.
     *
     * @param Action $action
     * @param Employee $employee
     */
    public function execute(Action $action, Employee $employee)
    {
        $this->action = $action;
        $this->employee = $employee;
        $this->step = $action->step;
        $this->flow = $action->step->flow;

        $this->determineTriggerDate();
        $this->scheduleAction();
    }

    private function determineTriggerDate(): void
    {
        if ($this->flow->trigger == Flow::TRIGGER_HIRING_DATE) {
            $this->date = $this->employee->hired_at;
        }
    }

    private function scheduleAction(): void
    {
        // real_number_of_days is either positive or negative, depending on the
        // step (a step can be before, or after a given date)
        if ($this->step->real_number_of_days >= 0) {
            $dateStepShouldBeTriggered = $this->date->copy()->addDays((int) $this->step->real_number_of_days);
        } else {
            $dateStepShouldBeTriggered = $this->date->copy()->subDays((int) $this->step->real_number_of_days * -1);
        }

        // if the flow is set for the anniversary of the given date, we
        // need to make sure that each step of the flow is executed in
        //the present or the future.
        // if it's not an anniversary, we simply don't process the step
        // in the past.
        if ($dateStepShouldBeTriggered->isPast() && ! $this->flow->is_triggered_on_anniversary) {
            return;
        }

        while ($dateStepShouldBeTriggered->isPast()) {
            $dateStepShouldBeTriggered->addYear();
        }

        ScheduledAction::create([
            'action_id' => $this->action->id,
            'employee_id' => $this->employee->id,
            'triggered_at' => $dateStepShouldBeTriggered,
            'content' => $this->action->content,
        ]);
    }
}
