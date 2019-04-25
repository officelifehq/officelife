<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Step;
use App\Services\BaseService;
use App\Models\Company\Action;
use App\Services\Company\Adminland\Company\LogAuditAction;

class AddActionToStep extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'flow_id' => 'required|integer|exists:flows,id',
            'step_id' => 'required|integer|exists:steps,id',
            'nature' => 'required|string',
            'recipient' => 'required|string',
            'specific_recipient_information' => 'required|string',
            'is_dummy' => 'nullable|boolean',
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
            config('homas.authorizations.hr')
        );

        $step = Step::where('company_id', $data['company_id'])
            ->where('flow_id', $data['flow_id'])
            ->findOrFail($data['step_id']);

        $action = Action::create([
            'step_id' => $data['step_id'],
            'nature' => $data['nature'],
            'recipient' => $data['recipient'],
            'specific_recipient_information' => $data['specific_recipient_information'],
        ]);

        $step->calculateDays();

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'action_created',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'flow_id' => $step->flow->id,
                'flow_name' => $step->flow->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $action;
    }
}
