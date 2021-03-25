<?php

namespace Tests\Unit\ViewHelpers\Company;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\HeaderSearchViewHelper;

class HeaderSearchViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
        ]);
        // the following should not be included in the search results
        Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'locked' => true,
            'company_id' => $michael->company_id,
        ]);

        $collection = HeaderSearchViewHelper::employees($michael->company, 'e');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                ],
            ],
            $collection->toArray()
        );

        $collection = HeaderSearchViewHelper::employees($michael->company, 'ale');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                ],
            ],
            $collection->toArray()
        );

        $collection = HeaderSearchViewHelper::employees($michael->company, 'l');
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight),
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'abc',
        ]);
        $teamB = Team::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'cde',
        ]);

        $collection = HeaderSearchViewHelper::teams($michael->company, 'a');
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

        $collection = HeaderSearchViewHelper::teams($michael->company, 'c');
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                ],
                1 => [
                    'id' => $teamB->id,
                    'name' => $teamB->name,
                ],
            ],
            $collection->toArray()
        );
    }
}
