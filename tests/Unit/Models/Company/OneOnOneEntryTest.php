<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\OneOnOneNote;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\OneOnOneActionItem;
use App\Models\Company\OneOnOneTalkingPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_manager(): void
    {
        $entry = OneOnOneEntry::factory()->create([]);
        $this->assertTrue($entry->manager()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $entry = OneOnOneEntry::factory()->create([]);
        $this->assertTrue($entry->employee()->exists());
    }

    /** @test */
    public function it_has_many_talking_points(): void
    {
        $entry = OneOnOneEntry::factory()->create([]);
        OneOnOneTalkingPoint::factory()->count(2)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->assertTrue($entry->talkingPoints()->exists());
    }

    /** @test */
    public function it_has_many_action_items(): void
    {
        $entry = OneOnOneEntry::factory()->create([]);
        OneOnOneActionItem::factory()->count(2)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->assertTrue($entry->actionItems()->exists());
    }

    /** @test */
    public function it_has_many_notes(): void
    {
        $entry = OneOnOneEntry::factory()->create([]);
        OneOnOneNote::factory()->count(2)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->assertTrue($entry->notes()->exists());
    }
}
