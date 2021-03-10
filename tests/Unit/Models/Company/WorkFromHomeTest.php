<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use App\Models\Company\WorkFromHome;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkFromHomeTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $workFromHome = WorkFromHome::factory()->create([]);
        $this->assertTrue($workFromHome->employee()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $entry = WorkFromHome::factory()->create([
            'employee_id' => $michael->id,
            'date' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $entry->id,
                'employee' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                ],
                'date' => '2020-01-12',
                'localized_date' => 'Sunday, Jan 12th 2020',
            ],
            $entry->toObject()
        );
    }
}
