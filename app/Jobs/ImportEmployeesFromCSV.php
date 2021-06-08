<?php

namespace App\Jobs;

use Throwable;
use App\Models\Company\File;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\Company\ImportJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use App\Models\Company\ImportJobReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEmployeesFromCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The import job to treat.
     *
     * @var array
     */
    public ImportJob $importJob;

    /**
     * The file to download.
     *
     * @var array
     */
    public File $file;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(ImportJob $importJob, File $file)
    {
        $this->importJob = $importJob;
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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
    public function failed(Throwable $exception)
    {
        $this->importJob->update([
            'status' => ImportJob::FAILED,
        ]);
    }

    private function readFile(): void
    {
        try {
            $response = Http::get($this->file->cdn_url.urlencode($this->file->name));
            Storage::disk('local')->put("imports/{$this->file->name}", $response->body());

            SimpleExcelReader::create(Storage::disk('local')->path("imports/{$this->file->name}"))
                ->trimHeaderRow()
                ->headersToSnakeCase()
                ->getRows()
                ->each(function (array $rowProperties) {
                    $this->handleRow($rowProperties);
                });
        } finally {
            if (Storage::disk('local')->exists("imports/{$this->file->name}")) {
                Storage::disk('local')->delete("imports/{$this->file->name}");
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
