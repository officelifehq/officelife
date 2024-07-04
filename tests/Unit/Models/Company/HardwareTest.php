<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use Tests\ApiTestCase;

class HardwareTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $hardware = Hardware::factory()->create();
        $this->assertTrue($hardware->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $dwight = Employee::factory()->create();
        $hardware = Hardware::factory()->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($hardware->employee()->exists());
    }
}
