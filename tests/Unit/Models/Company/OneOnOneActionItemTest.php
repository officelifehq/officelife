<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\OneOnOneActionItem;
use Tests\TestCase;

class OneOnOneActionItemTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $item = OneOnOneActionItem::factory()->create([]);
        $this->assertTrue($item->entry()->exists());
    }
}
