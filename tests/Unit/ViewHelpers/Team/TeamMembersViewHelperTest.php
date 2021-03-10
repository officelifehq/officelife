<?php

namespace Tests\Unit\ViewHelpers\Team;

use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Http\ViewHelpers\Team\TeamMembersViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamMembersViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $collection = TeamMembersViewHelper::searchedEmployees($michael->company->employees);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                ],
                1 => [
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
                'avatar' => AvatarHelper::getImage($michael),
                'position' => $michael->position,
            ],
            $array
        );
    }
}
