<?php

namespace Tests\Unit\ViewHelpers\Jobs;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;
use App\Http\ViewHelpers\Jobs\JobsCompanyViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobsCompanyViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_all_the_active_job_openings_in_the_company(): void
    {
        $company = Company::factory()->create([
            'name' => 'Last',
        ]);
        $team = Team::factory()->create();

        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'team_id' => $team->id,
            'active' => true,
        ]);

        $array = JobsCompanyViewHelper::index($company);

        $this->assertEquals(
            [
                'id' => $company->id,
                'name' => $company->name,
                'location' => $company->location,
                'logo' => null,
            ],
            $array['company']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $opening->id,
                    'reference_number' => $opening->reference_number,
                    'title' => $opening->title,
                    'team' => [
                        'name' => $team->name,
                    ],
                ],
            ],
            $array['job_openings']->toArray()
        );
    }
}
