<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;

class UpdateCompanyLogo extends BaseService
{
    protected File $file;
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
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Update the company logo.
     *
     * @param array $data
     * @return File
     */
    public function execute(array $data): File
    {
        $this->data = $data;

        $this->validate();
        $this->upload();
        $this->log();

        return $this->file;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->company = Company::find($this->data['company_id']);

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    private function upload(): void
    {
        Company::where('id', $this->company->id)->update([
            'logo_file_id' => $this->file->id,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'company_logo_changed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
        ])->onQueue('low');
    }
}
