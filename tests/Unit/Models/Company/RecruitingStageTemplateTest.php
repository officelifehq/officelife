<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecruitingStageTemplateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $template = RecruitingStageTemplate::factory()->create([]);
        $this->assertTrue($template->company()->exists());
    }

    /** @test */
    public function it_has_many_stages(): void
    {
        $template = RecruitingStageTemplate::factory()->create([]);
        RecruitingStage::factory()->count(2)->create([
            'recruiting_stage_template_id' => $template->id,
        ]);
        $this->assertTrue($template->stages()->exists());
    }
}
