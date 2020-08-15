<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RateYourManagerSurveyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_manager(): void
    {
        $entry = factory(RateYourManagerSurvey::class)->create([]);
        $this->assertTrue($entry->manager()->exists());
    }

    /** @test */
    public function it_has_many_answers(): void
    {
        $entry = factory(RateYourManagerSurvey::class)->create([]);
        factory(RateYourManagerAnswer::class, 2)->create([
            'rate_your_manager_survey_id' => $entry->id,
        ]);

        $this->assertTrue($entry->answers()->exists());
    }
}
