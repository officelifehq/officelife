<?php

namespace Tests\Unit\ViewHelpers\Company;

use Tests\TestCase;
use App\Models\Company\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\HeaderSearchViewHelper;

class HeaderSearchViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $collection = HeaderSearchViewHelper::employees($michael->company->employees);

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
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $collection = HeaderSearchViewHelper::teams($michael->company->teams);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                ],
            ],
            $collection->toArray()
        );
    }
}
