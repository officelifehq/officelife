<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use ErrorException;
use App\Services\BaseService;
use App\Models\Company\ImportJob;
use App\Models\Company\ImportJobReport;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class StoreEmployeesFromCSVInTemporaryTable extends BaseService
{
    private array $data;

    private ImportJob $importJob;

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
            'path' => 'required|string',
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

        return $this->importJob;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
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
            'import_started_at' => Carbon::now(),
        ]);

        $job = $this->importJob;

        try {
            SimpleExcelReader::create($this->data['path'])
                ->trimHeaderRow()
                ->headersToSnakeCase()
                ->getRows()
                ->each(function (array $rowProperties) use ($job) {
                    $skipEntry = false;

                    if (! $this->isValidEmail($rowProperties)) {
                        $skipEntry = true;
                    }

                    ImportJobReport::create([
                        'import_job_id' => $job->id,
                        'employee_first_name' => $rowProperties['first_name'],
                        'employee_last_name' => $rowProperties['last_name'],
                        'employee_email' => $rowProperties['email'],
                        'skipped_during_upload' => $skipEntry,
                        'skipped_during_upload_reason' => $skipEntry ? 'invalid_email' : null,
                    ]);
                });

            $this->importJob->status = ImportJob::MIGRATED;
        } catch (ErrorException $e) {
            $this->importJob->status = ImportJob::FAILED;
        }

        $this->importJob->import_ended_at = Carbon::now();
        $this->importJob->save();
    }

    private function isValidEmail(array $row): bool
    {
        $validator = Validator::make($row, ['email' => 'required|email:rfc']);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }
}
