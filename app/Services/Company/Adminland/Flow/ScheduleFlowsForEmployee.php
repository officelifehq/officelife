<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Services\BaseService;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

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
        $this->checkIfThereAreFlowsWithTheGivenTrigger();
        $this->checkFlows();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->employee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['employee_id']);
    }

    private function checkIfThereAreFlowsWithTheGivenTrigger(): void
    {
        $this->flows = Flow::where('company_id', $this->data['company_id'])
            ->where('type', Flow::DATE_BASED)
            ->where('trigger', $this->data['trigger'])
            ->with('steps')
            ->with('steps.actions')
            ->get();
    }

    private function checkFlows()
    {
        if ($this->flows->count() == 0) {
            return;
        }

        foreach ($this->flows as $flow) {
            foreach ($flow->steps as $step) {
                foreach ($step->actions as $action) {
                    (new ScheduleActionInTheFuture)->execute($action, $this->employee);
                }
            }
        }
    }
}
