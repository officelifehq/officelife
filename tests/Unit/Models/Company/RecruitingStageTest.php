<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\RecruitingStage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecruitingStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_template(): void
    {
        $stage = RecruitingStage::factory()->create([]);
        $this->assertTrue($stage->template()->exists());
    }
}
