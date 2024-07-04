<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\RecruitingStage;
use Tests\TestCase;

class RecruitingStageTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_template(): void
    {
        $stage = RecruitingStage::factory()->create([]);
        $this->assertTrue($stage->template()->exists());
    }
}
