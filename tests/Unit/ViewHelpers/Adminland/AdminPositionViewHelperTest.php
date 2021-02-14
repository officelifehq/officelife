<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminPositionViewHelper;

class AdminPositionViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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
