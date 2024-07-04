<?php

namespace Tests\Unit\Collections;

use App\Http\Collections\WorkFromHomeCollection;
use App\Models\Company\WorkFromHome;
use Tests\TestCase;

class WorkFromHomeCollectionTest extends TestCase
{
    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = $this->createAdministrator();
        WorkFromHome::factory()->count(2)->create([
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
