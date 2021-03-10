<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\RateYourManagerAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RateYourManagerAnswerTest extends TestCase
{
    use DatabaseTransactions;

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
