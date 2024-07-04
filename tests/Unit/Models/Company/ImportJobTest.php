<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ImportJob;
use App\Models\Company\ImportJobReport;
use Tests\TestCase;

class ImportJobTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $importJob = ImportJob::factory()->create([]);
        $this->assertTrue($importJob->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_author(): void
    {
        $importJob = ImportJob::factory()->create([]);
        $this->assertTrue($importJob->author()->exists());
    }

    /** @test */
    public function it_has_many_job_reports(): void
    {
        $importJob = ImportJob::factory()
            ->has(ImportJobReport::factory()->count(2), 'reports')
            ->create([]);

        $this->assertTrue($importJob->reports()->exists());
    }
}
