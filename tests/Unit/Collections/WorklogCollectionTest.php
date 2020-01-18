<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Http\Collections\WorklogCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = factory(Employee::class)->create([]);
        factory(Worklog::class, 2)->create([
            'employee_id' => $michael->id,
        ]);

        $worklogs = $michael->worklogs()->get();
        $collection = WorklogCollection::prepare($worklogs);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
