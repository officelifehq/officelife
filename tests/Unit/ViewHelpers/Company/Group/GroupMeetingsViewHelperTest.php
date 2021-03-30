<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Group\GroupMeetingsViewHelper;

class GroupMeetingsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_a_specific_meeting(): void
    {
        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
        ]);
        $meeting->employees()->attach([$michael->id]);

        $array = GroupMeetingsViewHelper::show($meeting, $group->company);

        $this->assertEquals(
            $meeting->id,
            $array['id']
        );

        $this->assertEquals(
            1,
            count($array['participants']->toArray())
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 23),
                    'attended' => false,
                    'was_a_guest' => false,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array['participants']->toArray()
        );
    }

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
        $meeting = Meeting::factory()->create([]);
        $meeting->employees()->attach([$michael->id]);

        $collection = GroupMeetingsViewHelper::potentialGuests($meeting, $michael->company, 'alb');
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
}
