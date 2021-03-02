<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\ImportJob;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\StoreEmployeesFromCSVInTemporaryTable;

class StoreEmployeesFromCSVInTemporaryTableTest extends TestCase
{
    use DatabaseTransactions;

    public function getStubPath(string $name): string
    {
        return base_path("tests/Stubs/Imports/{$name}");
    }

    /** @test */
    public function it_stores_employees_in_a_temporary_table_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, 'working.csv');
    }

    /** @test */
    public function it_stores_employees_in_a_temporary_table_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, 'working.csv');
    }

    /** @test */
    public function normal_employees_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, 'working.csv');
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
        (new StoreEmployeesFromCSVInTemporaryTable)->execute($request);
    }

    /** @test */
    public function it_does_nothing_if_it_imports_an_empty_file(): void
    {
        $michael = $this->createAdministrator();

        $job = (new StoreEmployeesFromCSVInTemporaryTable)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'path' => $this->getStubPath('empty.csv'),
        ]);

        $this->assertDatabaseHas('import_jobs', [
            'id' => $job->id,
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'status' => ImportJob::UPLOADED,
        ]);

        $this->assertDatabaseMissing('import_job_reports', [
            'import_job_id' => $job->id,
        ]);
    }

    private function executeService(Employee $michael, string $filename): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'path' => $this->getStubPath($filename),
        ];

        $job = (new StoreEmployeesFromCSVInTemporaryTable)->execute($request);

        $this->assertInstanceOf(
            ImportJob::class,
            $job
        );

        $this->assertDatabaseHas('import_jobs', [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'status' => ImportJob::UPLOADED,
        ]);

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $job->id,
            'employee_first_name' => 'Henri',
            'employee_last_name' => 'Troyat',
            'employee_email' => 'henri@troyat.com',
            'skipped_during_upload' => false,
            'skipped_during_upload_reason' => null,
        ]);

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $job->id,
            'employee_first_name' => 'Al',
            'employee_last_name' => 'Berri',
            'employee_email' => 'al@berri.com',
            'skipped_during_upload' => false,
            'skipped_during_upload_reason' => null,
        ]);

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $job->id,
            'employee_first_name' => 'Cata',
            'employee_last_name' => 'Strophe',
            'employee_email' => '',
            'skipped_during_upload' => true,
            'skipped_during_upload_reason' => 'invalid_email',
        ]);

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $job->id,
            'employee_first_name' => 'Regis',
            'employee_last_name' => 'Alexis',
            'employee_email' => 'regis@berri',
            'skipped_during_upload' => false,
            'skipped_during_upload_reason' => null,
        ]);

        $this->assertDatabaseHas('import_job_reports', [
            'import_job_id' => $job->id,
            'employee_first_name' => 'Eric',
            'employee_last_name' => 'Ramzy',
            'employee_email' => 'eric.ramzy',
            'skipped_during_upload' => true,
            'skipped_during_upload_reason' => 'invalid_email',
        ]);
    }
}
