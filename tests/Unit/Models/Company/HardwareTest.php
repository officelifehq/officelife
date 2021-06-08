<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HardwareTest extends ApiTestCase
{
    use DatabaseTransactions;

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
