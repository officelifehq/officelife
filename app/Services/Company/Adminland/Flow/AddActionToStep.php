<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Step;
use App\Services\BaseService;
use App\Models\Company\Action;

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
            'type' => 'required|string',
            'recipient' => 'required|string',
            'specific_recipient_information' => 'required|string',
        ];
    }

    /**
     * Add an action to a step.
     *
     * @param array $data
     * @return Action
     */
    public function execute(array $data): Action
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('kakene.authorizations.hr')
        );

        $step = Step::where('flow_id', $data['flow_id'])
            ->findOrFail($data['step_id']);

        $action = Action::create([
            'step_id' => $data['step_id'],
            'type' => $data['type'],
            'recipient' => $data['recipient'],
            'specific_recipient_information' => $data['specific_recipient_information'],
        ]);

        return $action;
    }
}
