<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SoftwareTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $software = Software::factory()->create();
        $this->assertTrue($software->company()->exists());
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $software = Software::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $software->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $software->company_id,
        ]);

        $software->employees()->syncWithoutDetaching([$dwight->id]);
        $software->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($software->employees()->exists());
    }

    /** @test */
    public function it_has_many_files(): void
    {
        $software = Software::factory()->create();

        $file = File::factory()->create();
        $software->files()->sync([$file->id]);

        $this->assertTrue($software->files()->exists());
    }
}
