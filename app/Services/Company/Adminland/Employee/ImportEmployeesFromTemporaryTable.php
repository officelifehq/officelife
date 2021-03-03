<?php

namespace App\Services\Company\Adminland\Employee;

use App\Services\BaseService;
use App\Models\Company\ImportJob;
use App\Jobs\AddEmployeeToCompany;
use App\Models\Company\ImportJobReport;

class ImportEmployeesFromTemporaryTable extends BaseService
{
    private array $data;

    private ImportJob $job;

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
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
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

        $this->job = ImportJob::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['import_job_id']);
    }

    /**
     * Import the employees.
     */
    private function import(): void
    {
        ImportJobReport::where('import_job_id', $this->data['import_job_id'])
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
                    ])->onQueue('low');
                });
            });
    }

    private function markAsMigrated(): void
    {
        $this->job->status = ImportJob::IMPORTED;
        $this->job->save();
    }
}
