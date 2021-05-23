<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Group\GroupShowViewHelper;

class GroupShowViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_the_group(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $tom = $this->createAnotherEmployee($michael);
        $pam = $this->createAnotherEmployee($michael);
        $jenny = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$michael->id]);
        $group->employees()->attach([$jim->id]);
        $group->employees()->attach([$tom->id]);
        $group->employees()->attach([$pam->id]);
        $group->employees()->attach([$jenny->id]);

        $array = GroupShowViewHelper::information($group, $group->company);

        $this->assertEquals(
            $group->id,
            $array['id']
        );

        $this->assertEquals(
            $group->name,
            $array['name']
        );

        $this->assertEquals(
            '<p>Employees happiness</p>',
            $array['mission']
        );

        $this->assertEquals(
            5,
            count($array['members']->toArray())
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id.'/edit',
            $array['url_edit']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id.'/delete',
            $array['url_delete']
        );
    }

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

        $collection = GroupShowViewHelper::meetings($group);

        $this->assertEquals(
            $meeting->id,
            $collection->toArray()[0]['id']
        );

        $this->assertEquals(
            'Meeting of Jan 01, 2019',
            $collection->toArray()[0]['happened_at']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id.'/meetings/'.$meeting->id,
            $collection->toArray()[0]['url']
        );

        $this->assertEquals(
            0,
            $collection->toArray()[0]['remaining_members_count']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'avatar' => ImageHelper::getAvatar($michael, 25),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $collection->toArray()[0]['preview_members']->toArray()
        );
    }
}
