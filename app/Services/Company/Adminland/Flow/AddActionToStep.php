<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Step;
use App\Services\BaseService;
use App\Models\Company\Action;
use Illuminate\Validation\Rule;

class AddActionToStep extends BaseService
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
            'flow_id' => 'required|integer|exists:flows,id',
            'step_id' => 'required|integer|exists:steps,id',
            'type' => [
                'required',
                Rule::in([
                    Action::TYPE_CREATE_TASK,
                    Action::TYPE_CREATE_PROJECT,
                ]),
            ],
            'content' => 'required|json',
        ];
    }

    /**
     * Add an action to a step.
     *
     * @param array $data
     *
     * @return Action
     */
    public function execute(array $data): Action
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        Step::where('flow_id', $data['flow_id'])
            ->findOrFail($data['step_id']);

        $action = Action::create([
            'step_id' => $data['step_id'],
            'type' => $data['type'],
            'content' => $data['content'],
        ]);

        return $action;
    }
}
