<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Http\ViewHelpers\Company\Group\GroupViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_list_of_groups(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$michael->id]);

        $array = GroupViewHelper::index($michael->company);

        $this->assertEquals(
            env('APP_URL') . '/' . $michael->company_id . '/company/groups/create',
            $array['url_create']
        );

        $this->assertEquals(
            $group->id,
            $array['data']->toArray()[0]['id']
        );

        $this->assertEquals(
            $group->name,
            $array['data']->toArray()[0]['name']
        );

        $this->assertEquals(
            $group->mission,
            $array['data']->toArray()[0]['mission']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/groups/'.$group->id,
            $array['data']->toArray()[0]['url']
        );

        $this->assertEquals(
            0,
            $array['data']->toArray()[0]['remaining_members_count']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'avatar' => ImageHelper::getAvatar($michael, 25),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array['data']->toArray()[0]['preview_members']->toArray()
        );
    }
}
