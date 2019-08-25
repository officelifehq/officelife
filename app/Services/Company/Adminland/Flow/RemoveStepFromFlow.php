<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Services\BaseService;

class RemoveStepFromFlow extends BaseService
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
            'author_id' => 'required|integer|exists:employees,id',
            'flow_id' => 'required|integer|exists:flows,id',
            'step_id' => 'required|integer|exists:steps,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove a step from a given flow.
     *
     * @param array $data
     * @return Flow
     */
    public function execute(array $data) : Flow
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $step = Step::where('flow_id', $data['flow_id'])
            ->findOrFail($data['step_id']);

        $flow = $step->flow;
        $step->delete();

        return $flow;
    }
}
