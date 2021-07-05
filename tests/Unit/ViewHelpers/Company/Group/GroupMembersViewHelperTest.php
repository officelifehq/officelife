<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Group\GroupMembersViewHelper;

class GroupMembersViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 0, 0, 0));
        $michael = $this->createAdministrator();

        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([
            $michael->id,
        ]);

        Carbon::setTestNow(Carbon::create(2018, 1, 1, 0, 0, 0));
        $jim = $this->createAnotherEmployee($michael);
        $group->employees()->attach([
            $jim->id,
        ]);

        $collection = GroupMembersViewHelper::members($group);
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                    'added_at' => DateHelper::formatDate($michael->created_at),
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim),
                    'added_at' => DateHelper::formatDate($jim->created_at),
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_potential_new_members(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $jean = Employee::factory()->create([
            'first_name' => 'jean',
            'company_id' => $michael->company_id,
        ]);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([
            $michael->id,
        ]);
        $group->employees()->attach([
            $jim->id,
        ]);

        $collection = GroupMembersViewHelper::potentialMembers($michael->company, $group, 'je');
        $this->assertEquals(
            [
                0 => [
                    'id' => $jean->id,
                    'name' => $jean->name,
                    'avatar' => ImageHelper::getAvatar($jean, 64),
                ],
            ],
            $collection->toArray()
        );

        $collection = GroupMembersViewHelper::potentialMembers($michael->company, $group, 'roger');
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }
}
