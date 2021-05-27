<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\ImportJob;
use App\Models\Company\ImportJobReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminUploadEmployeeViewHelper;

class AdminUploadEmployeeViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_past_csv_import_jobs(): void
    {
        $michael = $this->createAdministrator();
        $importJobA = ImportJob::factory()->create([
            'company_id' => $michael->company->id,
            'author_id' => null,
            'status' => ImportJob::IMPORTED,
            'import_started_at' => '2021-02-20 12:12:12',
            'import_ended_at' => '2021-02-20 12:12:12',
        ]);
        $importJobB = ImportJob::factory()->create([
            'company_id' => $michael->company->id,
            'status' => ImportJob::CREATED,
            'author_id' => $michael->id,
            'import_started_at' => null,
            'import_ended_at' => null,
        ]);
        ImportJobReport::factory()->count(2)->create([
            'import_job_id' => $importJobB->id,
        ]);

        $array = AdminUploadEmployeeViewHelper::index($michael->company, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $importJobB->id,
                    'author' => [
                        'name' => 'Dwight Schrute',
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'status' => 'created',
                    'status_translated' => 'created',
                    'number_of_entries' => 2,
                    'import_started_at' => null,
                    'import_ended_at' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/account/employees/upload/archives/'.$importJobB->id,
                ],
                1 => [
                    'id' => $importJobA->id,
                    'author' => [
                        'name' => null,
                        'url' => null,
                    ],
                    'status' => 'imported',
                    'status_translated' => 'imported',
                    'number_of_entries' => 0,
                    'import_started_at' => 'Feb 20, 2021 12:12 PM',
                    'import_ended_at' => 'Feb 20, 2021 12:12 PM',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/account/employees/upload/archives/'.$importJobA->id,
                ],
            ],
            $array['entries']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/account/employees/upload',
            $array['url_new']
        );
    }

    /** @test */
    public function it_gets_the_details_of_an_import_job(): void
    {
        $michael = $this->createAdministrator();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company->id,
            'author_id' => $michael->id,
            'status' => ImportJob::IMPORTED,
            'import_started_at' => '2021-02-20 12:12:12',
            'import_ended_at' => '2021-02-20 12:12:12',
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
            'skipped_during_upload_reason' => null,
        ]);
        $failedReport = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
            'skipped_during_upload' => true,
            'skipped_during_upload_reason' => 'invalid_email',
        ]);

        $array = AdminUploadEmployeeViewHelper::show($importJob, $michael);

        $this->assertEquals(
            $importJob->id,
            $array['id']
        );

        $this->assertEquals(
            [
                'name' => 'Dwight Schrute',
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
            ],
            $array['author']
        );

        $this->assertEquals(
            'imported',
            $array['status']
        );

        $this->assertEquals(
            2,
            $array['number_of_entries']
        );

        $this->assertEquals(
            'Feb 20, 2021 12:12 PM',
            $array['import_started_at']
        );

        $this->assertEquals(
            'Feb 20, 2021 12:12 PM',
            $array['import_ended_at']
        );

        $this->assertEquals(
            'Feb 20, 2021 12:12 PM',
            $array['import_ended_at']
        );

        $this->assertEquals(
            1,
            $array['number_of_entries_that_can_be_imported']
        );

        $this->assertEquals(
            1,
            $array['number_of_failed_entries']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $report->id,
                    'employee_first_name' => $report->employee_first_name,
                    'employee_last_name' => $report->employee_last_name,
                    'employee_email' => $report->employee_email,
                    'skipped_during_upload' => false,
                    'skipped_during_upload_reason' => null,
                ],
                1 => [
                    'id' => $failedReport->id,
                    'employee_first_name' => $failedReport->employee_first_name,
                    'employee_last_name' => $failedReport->employee_last_name,
                    'employee_email' => $failedReport->employee_email,
                    'skipped_during_upload' => true,
                    'skipped_during_upload_reason' => 'Invalid email',
                ],
            ],
            $array['first_five_entries']->toArray()
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $failedReport->id,
                    'employee_first_name' => $failedReport->employee_first_name,
                    'employee_last_name' => $failedReport->employee_last_name,
                    'employee_email' => $failedReport->employee_email,
                    'skipped_during_upload' => $failedReport->skipped_during_upload,
                    'skipped_during_upload_reason' => 'Invalid email',
                ],
            ],
            $array['failed_entries']->toArray()
        );
    }
}
