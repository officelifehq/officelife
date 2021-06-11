<?php

namespace App\Services\Company\Adminland\Employee;

use Throwable;
use Illuminate\Support\Str;
use App\Models\Company\File;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use App\Models\Company\ImportJob;
use App\Services\QueuableService;
use Illuminate\Support\Facades\Http;
use App\Services\DispatchableService;
use App\Models\Company\ImportJobReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportEmployeesFromCSV extends BaseService implements QueuableService
{
    use DispatchableService;

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
            'import_job_id' => 'required|integer|exists:import_jobs,id',
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Read the CSV file and put rows in the temporary table.
     *
     * @param array $data
     */
    public function init(array $data = []): self
    {
        $this->data = $data;
        $this->validate();

        return $this;
    }

    private function validate(): array
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $importJob = ImportJob::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['import_job_id']);

        $file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);

        return [$importJob, $file];
    }

    /**
     * Import the employees.
     */
    public function handle(): void
    {
        [$importJob, $file] = $this->validate();
        $this->importJob = $importJob;
        $this->file = $file;

        $this->importJob->update([
            'status' => ImportJob::STARTED,
            'import_started_at' => Carbon::now(),
        ]);

        $this->readFile();

        $this->importJob->update([
            'status' => ImportJob::UPLOADED,
            'import_ended_at' => Carbon::now(),
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void
    {
        if ($this->importJob !== null) {
            $this->importJob->update([
                'status' => ImportJob::FAILED,
            ]);
        }
    }

    private function readFile(): void
    {
        $filePath = "imports/{$this->file->name}";
        $reader = null;

        try {
            $response = Http::get(Str::finish($this->file->cdn_url, '/').urlencode($this->file->name));
            $response->throw();

            Storage::disk('local')->put($filePath, $response->body());

            $reader = SimpleExcelReader::create(Storage::disk('local')->path($filePath))
                ->trimHeaderRow()
                ->headersToSnakeCase();

            $reader->getRows()
                ->each(function (array $rowProperties) {
                    $this->handleRow($rowProperties);
                });
        } finally {
            if ($reader) {
                $reader->close();
            }
            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
        }
    }

    private function handleRow(array $rowProperties): void
    {
        $skipReason = '';

        if (! $this->isValidEmail($rowProperties)) {
            $skipReason = ImportJob::INVALID_EMAIL;
        }

        if ($this->isEmailAlreadyTaken($rowProperties) && $skipReason === '') {
            $skipReason = ImportJob::EMAIL_ALREADY_TAKEN;
        }

        ImportJobReport::create([
            'import_job_id' => $this->importJob->id,
            'employee_first_name' => $rowProperties['first_name'],
            'employee_last_name' => $rowProperties['last_name'],
            'employee_email' => $rowProperties['email'],
            'skipped_during_upload' => $skipReason !== '',
            'skipped_during_upload_reason' => $skipReason === '' ? null : $skipReason,
        ]);
    }

    private function isValidEmail(array $row): bool
    {
        $validator = Validator::make($row, ['email' => 'required|email:rfc']);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    private function isEmailAlreadyTaken(array $row): bool
    {
        // check if the email is already taken in the list that is being imported
        $importJobReportCount = $this->importJob->reports
            ->where('employee_email', $row['email'])
            ->count();

        if ($importJobReportCount > 0) {
            return true;
        }

        // check if the email is already taken from the list of existing employees
        $employeeCount = $this->importJob->company->employees
            ->where('email', $row['email'])
            ->count();

        if ($employeeCount > 0) {
            return true;
        }

        return false;
    }
}
