<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Company;
use App\Models\Company\EmployeeStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeStatusTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $employeeStatus = EmployeeStatus::factory()->create([]);
        $this->assertTrue($employeeStatus->company()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $dunder = Company::factory()->create([]);
        $status = EmployeeStatus::factory()->create([
            'company_id' => $dunder->id,
            'name' => 'dunder',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $status->id,
                'company' => [
                    'id' => $dunder->id,
                ],
                'name' => 'dunder',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $status->toObject()
        );
    }
}
