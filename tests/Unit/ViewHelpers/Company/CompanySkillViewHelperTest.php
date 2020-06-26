<?php

namespace Tests\Unit\ViewHelpers\Company;

use Tests\TestCase;
use App\Models\Company\Skill;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\CompanySkillViewHelper;

class CompanySkillViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_information_about_skills_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $skill = factory(Skill::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);
        $skill->employees()->attach([$michael->id]);

        $response = CompanySkillViewHelper::skills($michael->company);

        $this->assertArraySubset(
            [
                'name' => 'php',
                'number_of_employees' => 1,
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skill->id,
            ],
            $response->toArray()[0]
        );

        $this->assertArrayHasKey('id', $response->toArray()[0]);
        $this->assertArrayHasKey('name', $response->toArray()[0]);
        $this->assertArrayHasKey('number_of_employees', $response->toArray()[0]);
        $this->assertArrayHasKey('url', $response->toArray()[0]);
    }
}
