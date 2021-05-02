<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Tests\TestCase;
use App\Models\Company\Group;
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
}
