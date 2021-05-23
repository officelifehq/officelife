<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Carbon\Carbon;
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
    public function it_gets_a_list_of_meetings(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
            'happened_at' => Carbon::now(),
        ]);
        $meeting->employees()->attach([$michael->id]);

        $array = GroupMeetingsViewHelper::index($group);

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id.'/meetings/create',
            $array['url_new']
        );

        $this->assertEquals(
            $meeting->id,
            $array['meetings']->toArray()[0]['id']
        );

        $this->assertEquals(
            'Meeting of Jan 01, 2019',
            $array['meetings']->toArray()[0]['happened_at']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id.'/meetings/'.$meeting->id,
            $array['meetings']->toArray()[0]['url']
        );

        $this->assertEquals(
            0,
            $array['meetings']->toArray()[0]['remaining_members_count']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'avatar' => ImageHelper::getAvatar($michael, 25),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array['meetings']->toArray()[0]['preview_members']->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_a_specific_meeting(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $meeting = Meeting::factory()->create([
            'group_id' => $group->id,
            'happened_at' => Carbon::now(),
        ]);
        $meeting->employees()->attach([$michael->id]);

        $array = GroupMeetingsViewHelper::show($meeting, $group->company);

        $this->assertEquals(
            [
                'id' => $meeting->id,
                'happened_at' => 'Jan 01, 2019',
                'happened_at_max_year' => '2020',
                'happened_at_year' => '2019',
                'happened_at_month' => '1',
                'happened_at_day' => '1',
            ],
            $array['meeting']
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
