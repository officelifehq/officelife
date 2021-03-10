<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Skill;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SkillTest extends TestCase
{
    use DatabaseTransactions;

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
