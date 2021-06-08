<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\ImportJob;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ImportJobReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\ImportEmployeesFromTemporaryTable as ImportEmployeesFromTemporaryTableJob;

class ImportEmployeesFromTemporaryTableJobTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_execute_service_to_stores_employees_using_job(): void
    {
        Queue::fake();

        $michael = $this->createHR();
        $importJob = ImportJob::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $report = ImportJobReport::factory()->create([
            'import_job_id' => $importJob->id,
        ]);

        ImportEmployeesFromTemporaryTableJob::dispatch([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'import_job_id' => $importJob->id,
        ]);

        Queue::assertPushed(ImportEmployeesFromTemporaryTableJob::class);
    }
}
