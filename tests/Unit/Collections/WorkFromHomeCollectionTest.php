<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\WorkFromHome;
use App\Http\Collections\WorkFromHomeCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkFromHomeCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = factory(Employee::class)->create([]);
        factory(WorkFromHome::class, 2)->create([
            'employee_id' => $michael->id,
        ]);

        $entries = $michael->workFromHomes()->get();
        $collection = WorkFromHomeCollection::prepare($entries);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
