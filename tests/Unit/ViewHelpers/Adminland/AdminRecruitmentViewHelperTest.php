<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\ApiTestCase;
use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminRecruitmentViewHelper;

class AdminRecruitmentViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_templates(): void
    {
        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stageA = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        $stageB = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);

        $array = AdminRecruitmentViewHelper::index($michael->company);

        $this->assertEquals(
            $template->id,
            $array['templates']->toArray()[0]['id']
        );
        $this->assertEquals(
            $template->name,
            $array['templates']->toArray()[0]['name']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/account/recruitment/'.$template->id,
            $array['templates']->toArray()[0]['url']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $stageA->id,
                    'name' => $stageA->name,
                    'position' => 1,
                ],
                1 => [
                    'id' => $stageB->id,
                    'name' => $stageB->name,
                    'position' => 2,
                ],
            ],
            $array['templates']->toArray()[0]['stages']->toArray()
        );
        $this->assertEquals(
            [
                'store' => env('APP_URL').'/'.$michael->company_id.'/account/recruitment',
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_details_of_the_template(): void
    {
        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stageA = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        $stageB = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);

        $array = AdminRecruitmentViewHelper::show($michael->company, $template);

        $this->assertEquals(
            $template->id,
            $array['id']
        );
        $this->assertEquals(
            $template->name,
            $array['name']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $stageA->id,
                    'name' => $stageA->name,
                    'position' => 1,
                ],
                1 => [
                    'id' => $stageB->id,
                    'name' => $stageB->name,
                    'position' => 2,
                ],
            ],
            $array['stages']->toArray()
        );
    }
}
