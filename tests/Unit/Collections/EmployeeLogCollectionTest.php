<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Http\Collections\EmployeeLogCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeLogCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = factory(Employee::class)->create([]);
        factory(EmployeeLog::class, 2)->create([
            'employee_id' => $michael->id,
        ]);

        $logs = $michael->employeeLogs()->with('author')->get();
        $collection = EmployeeLogCollection::prepare($logs);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
