<?php

namespace App\Services\Company\Adminland\Employee;

use App\Models\Company\File;
use App\Services\BaseService;
use App\Models\Company\ImportJob;

class StoreEmployeesFromCSVInTemporaryTable extends BaseService
{
    private array $data;

    private ImportJob $importJob;

    private File $file;

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
     * Import a CSV file, containing employees, and store them in an Import Job.
     *
     * @param array $data
     * @return ImportJob
     */
    public function execute(array $data): ImportJob
    {
        $this->data = $data;
        $this->validate();
        $this->import();

        ImportEmployeesFromCSV::dispatch([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'import_job_id' => $this->importJob->id,
            'file_id' => $this->file->id,
        ]);

        return $this->importJob;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    /**
     * Import the CSV.
     */
    private function import(): void
    {
        $this->importJob = ImportJob::create([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'status' => ImportJob::CREATED,
            'author_name' => $this->author->name,
        ]);
    }
}
