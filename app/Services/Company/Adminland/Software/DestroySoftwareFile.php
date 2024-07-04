<?php

namespace App\Services\Company\Adminland\Software;

use App\Jobs\LogAccountAudit;
use App\Models\Company\File;
use App\Models\Company\Software;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroySoftwareFile extends BaseService
{
    protected array $data;

    protected File $file;

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
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Destroy a file associated with the software.
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyFile();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->software = Software::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['software_id']);

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    private function destroyFile(): void
    {
        /* @phpstan-ignore-next-line */
        $this->software->files()->detach($this->data['file_id']);
        $this->file->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'software_file_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $this->software->id,
                'software_name' => $this->software->name,
                'name' => $this->file->name,
            ]),
        ])->onQueue('low');
    }
}
