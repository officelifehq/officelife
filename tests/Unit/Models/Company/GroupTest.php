<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use Tests\TestCase;

class GroupTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $group = Group::factory()->create([]);
        $this->assertTrue($group->company()->exists());
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $group = Group::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $group->company_id,
        ]);

        $group->employees()->syncWithoutDetaching([$dwight->id]);

        $this->assertTrue($group->employees()->exists());
    }

    /** @test */
    public function it_has_many_meetings(): void
    {
        $group = Group::factory()->create();
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);

        $this->assertTrue($group->meetings()->exists());
    }
}
