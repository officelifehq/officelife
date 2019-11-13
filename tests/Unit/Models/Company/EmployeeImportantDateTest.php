<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeImportantDate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeImportantDateTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $date = factory(EmployeeImportantDate::class)->create([]);
        $this->assertTrue($date->employee()->exists());
    }
}
