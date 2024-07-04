<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\File;
use Tests\TestCase;

class FileTest extends TestCase
{
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
