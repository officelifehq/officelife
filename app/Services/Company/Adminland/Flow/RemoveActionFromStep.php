<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Services\BaseService;
use App\Models\Company\Action;

class RemoveActionFromStep extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'step_id' => 'required|integer|exists:steps,id',
            'action_id' => 'required|integer|exists:actions,id',
        ];
    }

    /**
     * Remove an action from a given step.
     *
     * @param array $data
     *
     * @return Step
     */
    public function execute(array $data): Step
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $action = Action::where('step_id', $data['step_id'])
            ->findOrFail($data['action_id']);

        Flow::where('company_id', $data['company_id'])
            ->findOrFail($action->step->flow->id);

        $step = $action->step;
        $action->delete();

        return $step;
    }
}
