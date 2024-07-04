<?php

namespace Tests\Unit\ViewHelpers\Team;

use App\Helpers\ImageHelper;
use App\Http\ViewHelpers\Team\TeamMembersViewHelper;
use App\Models\Company\Employee;
use App\Models\Company\Team;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use Tests\TestCase;

class TeamMembersViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_a_collection_of_potential_team_members(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'permission_level' => 100,
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        (new AddEmployeeToTeam)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $collection = TeamMembersViewHelper::searchPotentialTeamMembers($michael->company, $team, 'ale');
        $this->assertEquals(0, $collection->count());

        $collection = TeamMembersViewHelper::searchPotentialTeamMembers($michael->company, $team, 'alb');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_an_array_containing_information_about_the_employee(): void
    {
        $michael = $this->createAdministrator();

        $array = TeamMembersViewHelper::employee($michael);

        $this->assertEquals(4, count($array));

        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => ImageHelper::getAvatar($michael, 35),
                'position' => $michael->position,
            ],
            $array
        );
    }
}
