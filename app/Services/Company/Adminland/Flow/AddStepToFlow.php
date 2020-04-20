<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class AddStepToFlow extends BaseService
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
            'number' => 'nullable|integer',
            'unit_of_time' => [
                'nullable',
                Rule::in([
                    'days',
                    'weeks',
                    'months',
                ]),
            ],
            'modifier' => [
                'required',
                Rule::in([
                    'before',
                    'after',
                    'same_day',
                ]),
            ],
        ];
    }

    /**
     * Add a step to a flow.
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

        Flow::where('company_id', $data['company_id'])
            ->findOrFail($data['flow_id']);

        $step = Step::create([
            'flow_id' => $data['flow_id'],
            'number' => $data['number'],
            'unit_of_time' => $data['unit_of_time'],
            'modifier' => $data['modifier'],
        ]);

        $step->calculateDays();

        $step->refresh();

        return $step;
    }
}
