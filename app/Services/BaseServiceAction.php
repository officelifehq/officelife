<?php

namespace App\Services;

use App\Models\Company\Action;
use App\Models\Company\Employee;
use App\Models\Company\ScheduledAction;
use App\Exceptions\MissingInformationInJsonAction;
use App\Services\Company\Adminland\Flow\ScheduleActionInTheFuture;

abstract class BaseServiceAction
{
    /**
     * Get the keys that the JSON should contain in the action.
     *
     * @return array
     */
    public function keys(): array
    {
        return [];
    }

    /**
     * Check that the JSON has all the keys it needs to run the action.
     *
     * @param ScheduledAction $scheduledAction
     */
    public function validateJsonStructure(ScheduledAction $scheduledAction): void
    {
        foreach ($this->keys() as $key) {
            if (! array_key_exists($key, json_decode($scheduledAction->content, true))) {
                throw new MissingInformationInJsonAction();
            }
        }
    }

    /**
     * Mark a scheduled action as already processed.
     *
     * @param ScheduledAction $scheduledAction
     */
    public function markAsProcessed(ScheduledAction $scheduledAction): void
    {
        $scheduledAction->processed = true;
        $scheduledAction->save();
    }

    /**
     * Schedule a future iteration for the given action.
     * Does not run the action if the flow is not meant to have a recurrence.
     *
     * @param Action $action
     * @param Employee $employee
     */
    public function scheduleFutureIteration(Action $action, Employee $employee): void
    {
        if ($action->step->flow->anniversary) {
            (new ScheduleActionInTheFuture)->execute($action, $employee);
        }
    }
}
