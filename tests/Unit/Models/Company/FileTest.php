<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $file = File::factory()->create([]);
        $this->assertTrue($file->company()->exists());
    }
}
