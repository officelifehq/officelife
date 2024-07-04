<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\Skill;
use Tests\TestCase;

class SkillTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $skill = Skill::factory()->create([]);
        $this->assertTrue($skill->company()->exists());
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $skill = Skill::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $skill->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $skill->company_id,
        ]);

        $skill->employees()->syncWithoutDetaching([$dwight->id]);
        $skill->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($skill->employees()->exists());
    }
}
