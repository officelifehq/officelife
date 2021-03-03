<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ImportJobReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportJobReportTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_import_job(): void
    {
        $importJobReport = ImportJobReport::factory()->create([]);
        $this->assertTrue($importJobReport->importJob()->exists());
    }
}
