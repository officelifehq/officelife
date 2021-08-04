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
                    'url' => env('APP_URL') . '/jobs/' . $company->slug . '/jobs/' . $opening->slug,
                ],
            ],
            $array['job_openings']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_details_about_a_specific_job(): void
    {
        $company = Company::factory()->create([
            'name' => 'Last',
        ]);
        $team = Team::factory()->create();

        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'team_id' => $team->id,
            'description' => 'laravel',
            'active' => true,
        ]);

        $array = JobsCompanyViewHelper::show($company, $opening);

        $this->assertEquals(
            [
                'id' => $company->id,
                'name' => $company->name,
                'location' => $company->location,
                'logo' => null,
                'url' => env('APP_URL') . '/jobs/' . $company->slug,
            ],
            $array['company']
        );

        $this->assertEquals(
            [
                'id' => $opening->id,
                'reference_number' => $opening->reference_number,
                'title' => $opening->title,
                'description' => '<p>laravel</p>',
                'team' => [
                    'name' => $team->name,
                ],
            ],
            $array['job_opening']
        );

        $this->assertEquals(
            env('APP_URL') . '/jobs/' . $company->slug . '/jobs/' . $opening->slug.'/apply',
            $array['url_apply']
        );
    }

    /** @test */
    public function it_gets_the_details_needed_for_the_apply_page(): void
    {
        $company = Company::factory()->create([
            'name' => 'Last',
        ]);
        $team = Team::factory()->create();

        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'team_id' => $team->id,
            'description' => 'laravel',
            'active' => true,
        ]);

        $array = JobsCompanyViewHelper::apply($company, $opening);

        $this->assertEquals(
            [
                'id' => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'location' => $company->location,
                'logo' => null,
            ],
            $array['company']
        );

        $this->assertEquals(
            [
                'id' => $opening->id,
                'reference_number' => $opening->reference_number,
                'title' => $opening->title,
                'slug' => $opening->slug,
            ],
            $array['job_opening']
        );

        $this->assertEquals(
            env('APP_URL') . '/jobs',
            $array['url_all']
        );

        $this->assertEquals(
            env('APP_URL') . '/jobs/' . $company->slug,
            $array['url_company']
        );

        $this->assertEquals(
            env('APP_URL') . '/jobs/' . $company->slug . '/jobs/' . $opening->slug,
            $array['url_back']
        );
    }
}
