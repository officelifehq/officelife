<?php

namespace Tests\Unit\ViewHelpers\Company;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
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
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);
        $skill->employees()->attach([$michael->id]);

        $response = CompanySkillViewHelper::skills($michael->company);

        $this->assertArraySubset(
            [
                'id' => $skill->id,
                'name' => 'php',
                'number_of_employees' => 1,
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skill->id,
            ],
            $response->toArray()[0]
        );

        // locked employees shouldn't have their skills displayed
        $michael->locked = true;
        $michael->save();

        $response = CompanySkillViewHelper::skills($michael->company);

        $this->assertEquals(0, count($response));
    }

    /** @test */
    public function it_gets_the_details_of_an_employee_with_all_employees_associated_with_it(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team->employees()->attach([$michael->id]);

        $dwight = $this->createAnotherEmployee($michael);

        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);
        $skill->employees()->attach([$michael->id]);
        $skill->employees()->attach([$dwight->id]);

        $skillB = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'java',
        ]);
        $skillB->employees()->attach([$michael->id]);

        $response = CompanySkillViewHelper::employeesWithSkill($skill);

        // this is ugly, but i don't know how to test an array which has collections inside it
        $this->assertEquals(
            $michael->id,
            $response->toArray()[0]['id']
        );
        $this->assertEquals(
            $michael->name,
            $response->toArray()[0]['name']
        );
        $this->assertEquals(
            ImageHelper::getAvatar($michael, 65),
            $response->toArray()[0]['avatar']
        );
        $this->assertEquals(
            $michael->position->title,
            $response->toArray()[0]['position']['title']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $skillB->id,
                    'name' => 'java',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skillB->id,
                ],
            ],
            $response->toArray()[0]['skills']->toArray()
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$team->id,
                ],
            ],
            $response->toArray()[0]['teams']->toArray()
        );

        // dwight, now
        $this->assertEquals(
            $dwight->id,
            $response->toArray()[1]['id']
        );
        $this->assertEquals(
            $dwight->name,
            $response->toArray()[1]['name']
        );
        $this->assertEquals(
            ImageHelper::getAvatar($dwight, 65),
            $response->toArray()[1]['avatar']
        );
        $this->assertEquals(
            $dwight->position->title,
            $response->toArray()[1]['position']['title']
        );
        $this->assertEquals(
            [],
            $response->toArray()[1]['skills']->toArray()
        );
        $this->assertEquals(
            [],
            $response->toArray()[1]['teams']->toArray()
        );
    }
}
