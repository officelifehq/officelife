<?php

namespace Tests\Unit\ViewHelpers\Jobs;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;
use App\Http\ViewHelpers\Jobs\JobsViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_all_the_companies_with_open_job_openings_in_the_instance(): void
    {
        $company = Company::factory()->create([
            'name' => 'Last',
        ]);
        $otherCompany = Company::factory()->create([
            'name' => 'First',
        ]);
        Company::factory()->create([
            'name' => 'Middle',
        ]);

        JobOpening::factory()->count(3)->create([
            'company_id' => $company->id,
            'active' => true,
        ]);
        JobOpening::factory()->count(2)->create([
            'company_id' => $otherCompany->id,
            'active' => true,
        ]);
        JobOpening::factory()->create([
            'company_id' => $company->id,
            'fulfilled' => true,
            'active' => true,
        ]);
        JobOpening::factory()->create([
            'company_id' => $otherCompany->id,
            'active' => false,
        ]);

        $array = JobsViewHelper::index();

        $this->assertEquals(
            [
                0 => [
                    'id' => $otherCompany->id,
                    'name' => $otherCompany->name,
                    'location' => $otherCompany->location,
                    'logo' => null,
                    'count' => 2,
                    'url' => env('APP_URL').'/jobs/'.$otherCompany->slug,
                ],
                1 => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'location' => $company->location,
                    'logo' => null,
                    'count' => 3,
                    'url' => env('APP_URL').'/jobs/'.$company->slug,
                ],
            ],
            $array
        );
    }
}
