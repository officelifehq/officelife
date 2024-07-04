<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\OneOnOneNote;
use Tests\TestCase;

class OneOnOneNoteTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $note = OneOnOneNote::factory()->create([]);
        $this->assertTrue($note->entry()->exists());
    }
}
