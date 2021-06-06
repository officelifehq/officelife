<?php

namespace App\Jobs;

use App\Models\Company\File;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\Company\Employee;
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
        try {
            $this->importJob->import_started_at = Carbon::now();
            $this->importJob->save();

            $this->readFile();

            $this->importJob->status = ImportJob::UPLOADED;
        } catch (\ErrorException $e) {
            $this->importJob->status = ImportJob::FAILED;
        }

        $this->importJob->import_ended_at = Carbon::now();
        $this->importJob->save();
    }

    private function readFile(): void
    {
        try {
            $response = Http::get($this->file->cdn_url.urlencode($this->file->name));
            Storage::disk('local')->put($this->file->name, $response->body());

            SimpleExcelReader::create(Storage::disk('local')->path($this->file->name))
                ->trimHeaderRow()
                ->headersToSnakeCase()
                ->getRows()
                ->each(function (array $rowProperties) {
                    $this->handleRow($rowProperties);
                });
        } finally {
            if (Storage::disk('local')->exists($this->file->name)) {
                Storage::disk('local')->delete($this->file->name);
            }
        }
    }

    private function handleRow(array $rowProperties): void
    {
        $skipReason = '';

        if (! $this->isValidEmail($rowProperties)) {
            $skipReason = ImportJob::INVALID_EMAIL;
        }

        if ($this->isEmailAlreadyTaken($rowProperties) && $skipReason == '') {
            $skipReason = ImportJob::EMAIL_ALREADY_TAKEN;
        }

        ImportJobReport::create([
            'import_job_id' => $this->job->id,
            'employee_first_name' => $rowProperties['first_name'],
            'employee_last_name' => $rowProperties['last_name'],
            'employee_email' => $rowProperties['email'],
            'skipped_during_upload' => $skipReason == '' ? false : true,
            'skipped_during_upload_reason' => $skipReason == '' ? null : $skipReason,
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
        $importJob = ImportJobReport::where('import_job_id', $this->job->id)
            ->where('employee_email', $row['email'])
            ->first();

        if ($importJob) {
            return true;
        }

        // check if the email is already taken from the list of existing employees
        $employee = Employee::where('company_id', $this->job->company_id)
            ->where('email', $row['email'])
            ->first();

        if ($employee) {
            return true;
        }

        return false;
    }
}
