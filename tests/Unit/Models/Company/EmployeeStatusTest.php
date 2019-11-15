<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeStatusTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $employeeLog = factory(EmployeeStatus::class)->create([]);
        $this->assertTrue($employeeLog->company()->exists());
    }
}
