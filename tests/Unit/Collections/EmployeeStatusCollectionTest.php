<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\EmployeeStatus;
use App\Http\Collections\EmployeeStatusCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeStatusCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $statusA = factory(EmployeeStatus::class)->create([]);
        factory(EmployeeStatus::class)->create([
            'company_id' => $statusA->company_id,
        ]);

        $statuses = $statusA->company->employeeStatuses()->orderBy('name', 'asc')->get();
        $collection = EmployeeStatusCollection::prepare($statuses);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
