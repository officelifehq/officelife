<?php

namespace App\Services\Company\Adminland\Employee;

use Throwable;
use App\Services\BaseService;
use App\Models\Company\ImportJob;
use App\Services\QueuableService;
use App\Services\DispatchableService;
use App\Models\Company\ImportJobReport;

class ImportEmployeesFromTemporaryTable extends BaseService implements QueuableService
{
    use DispatchableService;

    private array $data;

    /**
     * @var ImportJob|null
     */
    private ?ImportJob $importJob = null;

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
        ];
    }

    /**
     * Take all employees that were put in the temporary table during the CSV
     * import, and import them as employees in the company.
     */
    public function handle(): void
    {
        $this->validate();

        $this->import();
        $this->markAsMigrated();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->importJob = ImportJob::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['import_job_id']);
    }

    /**
     * Import the employees.
     */
    private function import(): void
    {
        $this->importJob->reports()
            ->where('skipped_during_upload', false)
            ->chunk(100, function ($reports) {
                $reports->each(function (ImportJobReport $report) {
                    AddEmployeeToCompany::dispatch([
                        'company_id' => $this->data['company_id'],
                        'author_id' => $this->data['author_id'],
                        'email' => $report->employee_email,
                        'first_name' => $report->employee_first_name,
                        'last_name' => $report->employee_last_name,
                        'permission_level' => 300,
                        'send_invitation' => false,
                    ]);
                });
            });
    }

    private function markAsMigrated(): void
    {
        $this->importJob->update([
            'status' => ImportJob::IMPORTED,
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void
    {
        if ($this->importJob === null) {
            $this->importJob = ImportJob::where('company_id', $this->data['company_id'])
                ->find($this->data['import_job_id']);
        }
        if ($this->importJob !== null) {
            $this->importJob->update([
                'status' => ImportJob::FAILED,
            ]);
        }
    }
}
