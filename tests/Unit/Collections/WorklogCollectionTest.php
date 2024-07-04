<?php

namespace Tests\Unit\Collections;

use App\Http\Collections\WorklogCollection;
use App\Models\Company\Worklog;
use Tests\TestCase;

class WorklogCollectionTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = $this->createAdministrator();
        Worklog::factory()->count(2)->create([
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
