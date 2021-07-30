<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;

class UpdateCompanyLocation extends BaseService
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
            'location' => 'required|string|max:255',
        ];
    }

    /**
     * Update the company's location.
     *
     * @param array $data
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->data = $data;
        $this->validate();
        $this->updateLocation();
        $this->log();

        return $this->company;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->company = Company::find($this->data['company_id']);
    }

    private function updateLocation(): void
    {
        Company::where('id', $this->company->id)->update([
            'location' => $this->data['location'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'company_location_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'location' => $this->data['location'],
            ]),
        ])->onQueue('low');
    }
}
