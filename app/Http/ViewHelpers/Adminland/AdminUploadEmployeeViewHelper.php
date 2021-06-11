<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\ImportJob;
use App\Models\Company\ImportJobReport;

class AdminUploadEmployeeViewHelper
{
    /**
     * Get all the CSV imports in the account.
     *
     * @param Company $company
     * @param Employee $loggedEmployee
     * @return array|null
     */
    public static function index(Company $company, Employee $loggedEmployee): ?array
    {
        $jobs = $company->importJobs()->orderBy('id', 'desc')->get();

        $importJobsCollection = collect([]);
        foreach ($jobs as $importJob) {
            $count = ImportJobReport::where('import_job_id', $importJob->id)->count();

            $importJobsCollection->push([
                'id' => $importJob->id,
                'author' => [
                    'name' => is_null($importJob->author_id) ? $importJob->author_name : $importJob->author->name,
                    'url' => is_null($importJob->author_id) ? null : route('employees.show', [
                        'company' => $company,
                        'employee' => $importJob->author,
                    ]),
                ],
                'status' => $importJob->status,
                'number_of_entries' => $count,
                'import_started_at' => $importJob->import_started_at ? DateHelper::formatShortDateWithTime($importJob->import_started_at, $loggedEmployee->timezone) : null,
                'import_ended_at' => $importJob->import_ended_at ? DateHelper::formatShortDateWithTime($importJob->import_ended_at, $loggedEmployee->timezone) : null,
                'url' => route('account.employees.upload.archive.show', [
                    'company' => $company,
                    'archive' => $importJob,
                ]),
            ]);
        }

        return [
            'entries' => $importJobsCollection,
            'url_new' => route('account.employees.upload', [
                    'company' => $company,
                ]),
        ];
    }

    /**
     * Get all the details about a specific import job.
     * This page shows the first five imports, and all the reports that failed.
     *
     * @param ImportJob $importJob
     * @param Employee $loggedEmployee
     * @return array|null
     */
    public static function show(ImportJob $importJob, Employee $loggedEmployee): ?array
    {
        // all reports
        $allEntriesCount = ImportJobReport::where('import_job_id', $importJob->id)->count();

        // first five reports
        $firstFiveReports = $importJob->reports()->take(5)->get();
        $fiveFirstEntriesReportsCollection = collect([]);
        foreach ($firstFiveReports as $importJobReport) {
            $fiveFirstEntriesReportsCollection->push([
                'id' => $importJobReport->id,
                'employee_first_name' => $importJobReport->employee_first_name,
                'employee_last_name' => $importJobReport->employee_last_name,
                'employee_email' => $importJobReport->employee_email,
                'skipped_during_upload' => $importJobReport->skipped_during_upload,
                'skipped_during_upload_reason' => $importJobReport->skipped_during_upload ? trans('account.import_employees_archives_item_status_'.$importJobReport->skipped_during_upload_reason) : null,
            ]);
        }

        // failed reports
        $failedReports = $importJob->reports()->where('skipped_during_upload', true)->get();
        $failedJobReportsCollection = collect([]);
        foreach ($failedReports as $importJobReport) {
            $failedJobReportsCollection->push([
                'id' => $importJobReport->id,
                'employee_first_name' => $importJobReport->employee_first_name,
                'employee_last_name' => $importJobReport->employee_last_name,
                'employee_email' => $importJobReport->employee_email,
                'skipped_during_upload' => $importJobReport->skipped_during_upload,
                'skipped_during_upload_reason' => trans('account.import_employees_archives_item_status_'.$importJobReport->skipped_during_upload_reason),
            ]);
        }

        return [
            'id' => $importJob->id,
            'author' => [
                'name' => is_null($importJob->author_id) ? $importJob->author_name : $importJob->author->name,
                'url' => is_null($importJob->author_id) ? null : route('employees.show', [
                    'company' => $importJob->company_id,
                    'employee' => $importJob->author,
                ]),
            ],
            'status' => $importJob->status,
            'import_started_at' => $importJob->import_started_at ? DateHelper::formatShortDateWithTime($importJob->import_started_at, $loggedEmployee->timezone) : null,
            'import_ended_at' => $importJob->import_ended_at ? DateHelper::formatShortDateWithTime($importJob->import_ended_at, $loggedEmployee->timezone) : null,
            'number_of_entries' => $allEntriesCount,
            'number_of_entries_that_can_be_imported' => $allEntriesCount - $failedJobReportsCollection->count(),
            'number_of_failed_entries' => $failedJobReportsCollection->count(),
            'first_five_entries' => $fiveFirstEntriesReportsCollection,
            'failed_entries' => $failedJobReportsCollection,
        ];
    }
}
