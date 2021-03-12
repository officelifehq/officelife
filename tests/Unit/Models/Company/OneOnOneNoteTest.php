<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\OneOnOneNote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneOnOneNoteTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $note = OneOnOneNote::factory()->create([]);
        $this->assertTrue($note->entry()->exists());
    }
}
