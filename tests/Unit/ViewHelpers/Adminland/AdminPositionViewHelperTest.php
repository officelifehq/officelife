<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use App\Http\ViewHelpers\Adminland\AdminPositionViewHelper;
use App\Models\Company\Position;
use Tests\TestCase;

class AdminPositionViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_a_collection_of_positions(): void
    {
        $position = Position::factory()->create([]);

        $collection = AdminPositionViewHelper::list($position->company);

        $this->assertEquals(
            [
                'id' => $position->id,
                'title' => $position->title,
            ],
            $collection->toArray()[0]
        );
    }
}
