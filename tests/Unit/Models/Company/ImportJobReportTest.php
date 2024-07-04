<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ImportJobReport;
use Tests\TestCase;

class ImportJobReportTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_import_job(): void
    {
        $importJobReport = ImportJobReport::factory()->create([]);
        $this->assertTrue($importJobReport->importJob()->exists());
    }
}
