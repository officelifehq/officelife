<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;

class UpdateCompanyFoundedDate extends BaseService
{
    protected Company $company;

    protected array $data;

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
            'year' => 'required|date_format:Y',
        ];
    }

    /**
     * Update the company's founded at date.
     *
     * @param array $data
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->data = $data;

        $this->company = Company::find($data['company_id']);

        $this->updateDate();

        $this->log();

        return $this->company;
    }

    private function updateDate(): void
    {
        $date = Carbon::createFromDate($this->data['year'], 1, 1)->startOfDay();

        Company::where('id', $this->company->id)->update([
            'founded_at' => $date,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'company_founded_date_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'founded_at' => $this->data['year'],
            ]),
        ])->onQueue('low');
    }
}
