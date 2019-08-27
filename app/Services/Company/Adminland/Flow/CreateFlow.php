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
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'name' => 'required|string|max:255',
            'type' => [
                'required',
                Rule::in([
                    'employee_joins_company',
                    'employee_leaves_company',
                    'employee_birthday',
                    'employee_joins_team',
                    'employee_leaves_team',
                    'employee_becomes_manager',
                    'employee_new_position',
                    'employee_leaves_holidays',
                    'employee_returns_holidays',
                    'employee_returns_leave',
                ]),
            ],
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a flow.
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

        $flow = Flow::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'type' => $data['type'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'flow_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'flow_id' => $flow->id,
                'flow_name' => $flow->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $flow;
    }
}
