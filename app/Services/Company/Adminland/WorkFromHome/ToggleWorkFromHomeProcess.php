<?php

namespace App\Services\Company\Adminland\WorkFromHome;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;

class ToggleWorkFromHomeProcess extends BaseService
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
        ];
    }

    /**
     * Toggle the work from home process in the company.
     *
     * @param array $data
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $company = Company::findOrFail($data['company_id']);

        $company->work_from_home_enabled = ! $company->work_from_home_enabled;
        $company->save();
        $company->refresh();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'toggle_work_from_home_process',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
        ])->onQueue('low');

        return $company;
    }
}
