<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Models\Company\ImportJob;
use Illuminate\Support\Facades\Http;
use App\Models\Company\ImportJobReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\ImportEmployeesFromCSV;

class ImportEmployeesFromCSVTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stores_employees_in_a_temporary_table_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
        ]);
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
            'cdn_url' => 'http://url.com/',
            'name' => 'working.csv',
        ]);

        $this->executeService($michael, $importJob, $file);
    }

    /** @test */
    public function it_stores_employees_in_a_temporary_table_as_hr(): void
    {
        $michael = $this->createHR();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
        ]);
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
            'cdn_url' => 'http://url.com/',
            'name' => 'working.csv',
        ]);

        $this->executeService($michael, $importJob, $file);
    }

    /** @test */
    public function normal_employees_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
        ]);
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
            'cdn_url' => 'http://url.com/',
            'name' => 'working.csv',
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $importJob, $file);
    }

    /** @test */
    public function it_fails_if_file_not_exist(): void
    {
        $michael = $this->createHR();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
        ]);
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
            'cdn_url' => 'http://url.com/',
            'name' => 'bad',
        ]);

        Storage::fake('local');

        Http::fake([
            'url.com/bad' => Http::response('', 404),
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'import_job_id' => $importJob->id,
            'file_id' => $file->id,
        ];

        $this->expectException(RequestException::class);
        (new ImportEmployeesFromCSV)->init($request)->execute();
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new ImportEmployeesFromCSV)->init($request)->execute();
    }

    private function executeService(Employee $michael, ImportJob $importJob, File $file): void
    {
        Storage::fake('local');

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Employee/Imports/working.csv'));
        Http::fake([
            'url.com/working.csv' => Http::response($body, 200),
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'import_job_id' => $importJob->id,
            'file_id' => $file->id,
        ];

        (new ImportEmployeesFromCSV)->init($request)->execute();

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $importJob->id,
            'employee_first_name' => 'Eric',
            'employee_last_name' => 'Ramzy',
            'employee_email' => 'eric.ramzy',
            'skipped_during_upload' => 1,
            'skipped_during_upload_reason' => 'invalid_email',
        ]);
        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $importJob->id,
            'employee_first_name' => 'Henri',
            'employee_last_name' => 'Troyat',
            'employee_email' => 'henri@troyat.com',
            'skipped_during_upload' => 0,
            'skipped_during_upload_reason' => null,
        ]);
    }
}
