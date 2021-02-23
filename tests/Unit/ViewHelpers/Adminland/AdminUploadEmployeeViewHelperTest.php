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

        $array = AdminUploadEmployeeViewHelper::index($michael->company);

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
                    'import_started_at' => 'Feb 20, 2021 12:12',
                    'import_ended_at' => 'Feb 20, 2021 12:12',
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
        ]);

        $array = AdminUploadEmployeeViewHelper::show($importJob);

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
            1,
            $array['number_of_entries']
        );

        $this->assertEquals(
            'Feb 20, 2021 12:12',
            $array['import_started_at']
        );

        $this->assertEquals(
            'Feb 20, 2021 12:12',
            $array['import_ended_at']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $report->id,
                    'employee_first_name' => $report->employee_first_name,
                    'employee_last_name' => $report->employee_last_name,
                    'employee_email' => $report->employee_email,
                    'skipped_during_upload' => $report->skipped_during_upload,
                    'skipped_during_upload_reason' => $report->skipped_during_upload_reason,
                ],
            ],
            $array['entries']->toArray()
        );
    }
}
