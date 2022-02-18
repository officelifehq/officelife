<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class AddStepToFlow extends BaseService
{
    private Step $step;

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
                    Step::UNIT_DAY,
                    Step::UNIT_WEEK,
                    Step::UNIT_MONTH,
                ]),
            ],
            'modifier' => [
                'required',
                Rule::in([
                    Step::MODIFIER_BEFORE,
                    Step::MODIFIER_AFTER,
                    Step::MODIFIER_SAME_DAY,
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

        $this->step = Step::create([
            'flow_id' => $data['flow_id'],
            'number' => $this->valueOrNull($data, 'number'),
            'unit_of_time' => $this->valueOrNull($data, 'unit_of_time'),
            'modifier' => $data['modifier'],
        ]);

        $this->calculateRealDays();
        $this->step->save();

        return $this->step;
    }

    /**
     * Calculate the real number of days represented by the step.
     */
    private function calculateRealDays(): void
    {
        if ($this->step->modifier == Step::MODIFIER_SAME_DAY) {
            $this->step->real_number_of_days = 0;
        }

        if ($this->step->modifier == Step::MODIFIER_BEFORE) {
            if ($this->step->unit_of_time == Step::UNIT_DAY) {
                $this->step->real_number_of_days = $this->step->number * -1;
            }

            if ($this->step->unit_of_time == Step::UNIT_WEEK) {
                $this->step->real_number_of_days = $this->step->number * 7 * -1;
            }

            if ($this->step->unit_of_time == Step::UNIT_MONTH) {
                $this->step->real_number_of_days = $this->step->number * 30 * -1;
            }
        }

        if ($this->step->modifier == Step::MODIFIER_AFTER) {
            if ($this->step->unit_of_time == Step::UNIT_DAY) {
                $this->step->real_number_of_days = $this->step->number;
            }

            if ($this->step->unit_of_time == Step::UNIT_WEEK) {
                $this->step->real_number_of_days = $this->step->number * 7;
            }

            if ($this->step->unit_of_time == Step::UNIT_MONTH) {
                $this->step->real_number_of_days = $this->step->number * 30;
            }
        }
    }
}
