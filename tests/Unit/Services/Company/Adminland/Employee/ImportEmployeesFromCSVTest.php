<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Models\Company\ImportJob;
use Illuminate\Support\Facades\Http;
use App\Models\Company\ImportJobReport;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\MalformedCSVException;
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
    public function it_fails_if_file_does_not_exist(): void
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
        (new ImportEmployeesFromCSV($request))->handle();
    }

    /** @test */
    public function it_fails_if_csv_does_not_have_the_right_header(): void
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
            'name' => 'malformed.csv',
        ]);

        Storage::fake('local');

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Employee/Imports/malformed.csv'));
        Http::fake([
            'url.com/malformed.csv' => Http::response($body, 200),
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'import_job_id' => $importJob->id,
            'file_id' => $file->id,
        ];

        $this->expectException(MalformedCSVException::class);
        (new ImportEmployeesFromCSV($request))->handle();
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
        (new ImportEmployeesFromCSV($request))->handle();
    }

    /** @test */
    public function it_saves_state_when_failing(): void
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

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'import_job_id' => $importJob->id,
            'file_id' => $file->id,
        ];

        try {
            $this->expectException(NotEnoughPermissionException::class);
            ImportEmployeesFromCSV::dispatch($request);
        } finally {
            $this->assertDatabaseHas('import_jobs', [
                'id' => $importJob->id,
                'status' => 'failed',
            ]);
        }
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

        (new ImportEmployeesFromCSV($request))->handle();

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
