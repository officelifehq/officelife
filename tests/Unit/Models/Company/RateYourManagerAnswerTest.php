<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\RateYourManagerAnswer;
use Tests\TestCase;

class RateYourManagerAnswerTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_entry(): void
    {
        $answer = RateYourManagerAnswer::factory()->create([]);
        $this->assertTrue($answer->entry()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $answer = RateYourManagerAnswer::factory()->create([]);
        $this->assertTrue($answer->employee()->exists());
    }
}
