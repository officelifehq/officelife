<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
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

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $michael = Employee::factory()->create();
        $file = File::factory()->create([
            'uploader_employee_id' => $michael->id,
        ]);

        $this->assertTrue($file->uploader()->exists());
    }
}
