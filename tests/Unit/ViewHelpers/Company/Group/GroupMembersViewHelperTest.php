<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Tests\TestCase;
use App\Helpers\DateHelper;
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
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([
            $michael->id,
        ]);
        $group->employees()->attach([
            $jim->id,
        ]);

        $collection = GroupMembersViewHelper::members($group);
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
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
                    'avatar' => $jim->avatar,
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

        $collection = GroupMembersViewHelper::potentialMembers('je', $group, $michael->company);
        $this->assertEquals(
            [
                0 => [
                    'id' => $jean->id,
                    'name' => $jean->name,
                    'avatar' => $jean->avatar,
                ],
            ],
            $collection->toArray()
        );

        $collection = GroupMembersViewHelper::potentialMembers('roger', $group, $michael->company);
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }
}
