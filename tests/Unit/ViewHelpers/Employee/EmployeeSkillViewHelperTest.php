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
        $this->assertEquals(1, $collection->count());
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
        $this->assertEquals(0, $collection->count());
    }
}
