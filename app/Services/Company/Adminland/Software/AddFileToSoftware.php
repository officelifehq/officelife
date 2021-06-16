<?php

namespace App\Services\Company\Adminland\Software;

use Carbon\Carbon;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Software;

class AddFileToSoftware extends BaseService
{
    protected array $data;
    protected Software $software;
    protected File $file;

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
            'software_id' => 'required|integer|exists:softwares,id',
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Add the given file to the software.
     *
     * @param array $data
     * @return File
     */
    public function execute(array $data): File
    {
        $this->data = $data;
        $this->validate();
        $this->assign();
        $this->log();

        return $this->file;
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

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    private function assign(): void
    {
        /* @phpstan-ignore-next-line */
        $this->software->files()->syncWithoutDetaching([
            $this->data['file_id'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'file_added_to_software',
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
