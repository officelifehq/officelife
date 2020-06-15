<?php

namespace Tests\Unit\ViewHelpers\Team;

use Tests\TestCase;
use App\Http\ViewHelpers\Team\TeamMembersViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamMembersViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = $this->createAdministrator();

        $collection = TeamMembersViewHelper::searchedEmployees($michael->company->employees);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_detail_of_an_employee(): void
    {
        $michael = $this->createAdministrator();

        $details = TeamMembersViewHelper::employee($michael);

        $this->assertEquals(4, count($details));
        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => $michael->avatar,
                'position' => $michael->position,
            ],
            $details
        );
    }
}
