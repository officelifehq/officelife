<?php

namespace App\Services\Company\Adminland\Software;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Software;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroySoftware extends BaseService
{
    protected array $data;

    protected Software $software;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'software_id' => 'required|integer|exists:softwares,id',
        ];
    }

    /**
     * Destroy a software.
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyFiles();
        $this->destroy();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->software = Software::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['software_id']);
    }

    private function destroy(): void
    {
        $this->software->delete();
    }

    private function destroyFiles(): void
    {
        $files = $this->software->files;
        foreach ($files as $file) {
            $file->delete();
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'software_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $this->software->id,
                'software_name' => $this->software->name,
            ]),
        ])->onQueue('low');
    }
}
