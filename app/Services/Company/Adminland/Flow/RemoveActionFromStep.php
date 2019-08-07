<?php

namespace App\Services\Company\Adminland\Flow;

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
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'step_id' => 'required|integer|exists:steps,id',
            'action_id' => 'required|integer|exists:actions,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove an action from a given step.
     *
     * @param array $data
     * @return Step
     */
    public function execute(array $data) : Step
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $action = Action::where('step_id', $data['step_id'])
            ->findOrFail($data['action_id']);

        $step = $action->step;
        $action->delete();

        return $step;
    }
}
