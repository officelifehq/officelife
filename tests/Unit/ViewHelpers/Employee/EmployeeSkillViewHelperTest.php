<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Tests\TestCase;
use App\Models\Company\Skill;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeSkillViewHelper;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;

class EmployeeSkillViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_handles_an_empty_search(): void
    {
        $michael = Employee::factory()->create();
        $collection = EmployeeSkillViewHelper::search($michael->company, $michael, null);
        $this->assertCount(0, $collection);
    }

    /** @test */
    public function it_searches_skills(): void
    {
        $michael = Employee::factory()->create();
        $skillPHP = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);

        (new AttachEmployeeToSkill)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'name' => 'java',
        ]);

        $collection = EmployeeSkillViewHelper::search($michael->company, $michael, 'p');
        $this->assertCount(1, $collection);
        $this->assertEquals(
            [
                0 => [
                    'id' => $skillPHP->id,
                    'name' => $skillPHP->name,
                ],
            ],
            $collection->toArray()
        );

        $collection = EmployeeSkillViewHelper::search($michael->company, $michael, 'z');
        $this->assertCount(0, $collection);
    }

    /** @test */
    public function it_does_not_return_current_skills(): void
    {
        $michael = Employee::factory()->create();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'javascript',
        ]);

        (new AttachEmployeeToSkill)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'name' => 'java',
        ]);

        $collection = EmployeeSkillViewHelper::search($michael->company, $michael, 'ja');
        $this->assertCount(1, $collection);
        $this->assertEquals(
            [
                0 => [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ],
            ],
            $collection->toArray()
        );
    }
}
