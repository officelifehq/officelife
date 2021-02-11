<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportJobTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $importJob = File::factory()->create([]);
        $this->assertTrue($importJob->company()->exists());
    }
}
