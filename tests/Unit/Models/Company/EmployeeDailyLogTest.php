<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeDailyLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeDailyLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $date = factory(EmployeeDailyLog::class)->create([]);
        $this->assertTrue($date->employee()->exists());
    }
}
