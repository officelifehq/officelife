<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Services\BaseService;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ScheduledAction;

class ScheduleFlowsForEmployee extends BaseService
{
    private array $data;
    private Employee $employee;
    private Collection $flows;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'trigger' => 'required|string',
        ];
    }

    /**
     * Schedules all the actions for all the flows that match a given trigger
     * type for a given employee.
     * Flow::TRIGGER_HIRING_DATE for instance.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->getAllFlowsWithTheGivenTrigger();
        $this->processFlows();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->employee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['employee_id']);
    }

    private function getAllFlowsWithTheGivenTrigger(): void
    {
        $this->flows = Flow::where('company_id', $this->data['company_id'])
            ->where('type', Flow::DATE_BASED)
            ->where('trigger', $this->data['trigger'])
            ->with('steps')
            ->with('steps.actions')
            ->get();
    }

    private function processFlows()
    {
        if ($this->flows->count() == 0) {
            return;
        }

        foreach ($this->flows as $flow) {
            $this->destroyUnprocessedScheduledActions($flow);

            foreach ($flow->steps as $step) {
                foreach ($step->actions as $action) {
                    (new ScheduleActionInTheFuture)->execute($action, $this->employee);
                }
            }
        }
    }

    private function destroyUnprocessedScheduledActions(Flow $flow): void
    {
        $actions = DB::table('actions')
            ->join('steps', 'steps.id', '=', 'actions.step_id')
            ->join('flows', 'flows.id', '=', 'steps.flow_id')
            ->where('flows.id', $flow->id)
            ->select('actions.id')
            ->pluck('actions.id')
            ->toArray();

        $unprocessedScheduledActions = ScheduledAction::where('processed', false)
            ->whereIn('action_id', $actions)
            ->where('employee_id', $this->employee->id)
            ->select('id')
            ->pluck('id')
            ->toArray();

        DB::table('scheduled_actions')->whereIn('id', $unprocessedScheduledActions)->delete();
    }
}
