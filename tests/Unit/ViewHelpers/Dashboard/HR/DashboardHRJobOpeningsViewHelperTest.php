<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Tests\TestCase;
use App\Models\Company\Company;
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
}
