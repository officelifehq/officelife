<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\OneOnOneActionItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneOnOneActionItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $item = factory(OneOnOneActionItem::class)->create([]);
        $this->assertTrue($item->entry()->exists());
    }
}
