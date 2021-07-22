<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRJobOpeningsViewHelper;

class DashboardHRJobOpeningsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_positions(): void
    {
        $company = Company::factory()->create();

        $positionA = Position::factory()->create([
            'company_id' => $company->id,
            'title' => 'Avenger',
        ]);
        $positionB = Position::factory()->create([
            'company_id' => $company->id,
            'title' => 'Boyfriend',
        ]);

        $collection = DashboardHRJobOpeningsViewHelper::positions($company);

        $this->assertEquals(
            2,
            $collection->count()
        );

        $this->assertEquals(
            [
                0 => [
                    'value' => $positionA->id,
                    'label' => 'Avenger',
                ],
                1 => [
                    'value' => $positionB->id,
                    'label' => 'Boyfriend',
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

        $collection = DashboardHRJobOpeningsViewHelper::potentialSponsors($michael->company, 'je');
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

        $collection = DashboardHRJobOpeningsViewHelper::potentialSponsors($michael->company, 'roger');
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $company = Company::factory()->create();

        $teamA = Team::factory()->create([
            'company_id' => $company->id,
            'name' => 'A',
        ]);
        $teamB = Team::factory()->create([
            'company_id' => $company->id,
            'name' => 'B',
        ]);

        $collection = DashboardHRJobOpeningsViewHelper::teams($company);

        $this->assertEquals(
            2,
            $collection->count()
        );

        $this->assertEquals(
            [
                0 => [
                    'value' => $teamA->id,
                    'label' => 'A',
                ],
                1 => [
                    'value' => $teamB->id,
                    'label' => 'B',
                ],
            ],
            $collection->toArray()
        );
    }
}
