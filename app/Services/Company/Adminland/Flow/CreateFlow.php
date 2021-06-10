<?php

namespace App\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use App\Models\Company\Flow;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class CreateFlow extends BaseService
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
            'name' => 'required|string|max:255',
            'type' => [
                'required',
                Rule::in([
                    Flow::DATE_BASED,
                    Flow::EVENT_BASED,
                ]),
            ],
            'trigger' => [
                'required',
                Rule::in([
                    Flow::TRIGGER_HIRING_DATE,
                ]),
            ],
            'anniversary' => 'required|boolean',
        ];
    }

    /**
     * Create a flow.
     *
     * @param array $data
     *
     * @return Flow
     */
    public function execute(array $data): Flow
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $flow = Flow::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'trigger' => $data['trigger'],
            'anniversary' => $data['anniversary'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'flow_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'flow_id' => $flow->id,
                'flow_name' => $flow->name,
            ]),
        ])->onQueue('low');

        return $flow;
    }
}
