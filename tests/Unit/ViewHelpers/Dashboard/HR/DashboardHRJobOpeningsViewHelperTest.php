<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use App\Models\Company\RecruitingStageTemplate;
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
    public function it_gets_a_collection_of_recruiting_templates(): void
    {
        $company = Company::factory()->create();

        $templateA = RecruitingStageTemplate::factory()->create([
            'company_id' => $company->id,
            'name' => 'Avenger',
        ]);
        $templateB = RecruitingStageTemplate::factory()->create([
            'company_id' => $company->id,
            'name' => 'Boyfriend',
        ]);

        $collection = DashboardHRJobOpeningsViewHelper::templates($company);

        $this->assertEquals(
            2,
            $collection->count()
        );

        $this->assertEquals(
            [
                0 => [
                    'value' => $templateA->id,
                    'label' => 'Avenger',
                ],
                1 => [
                    'value' => $templateB->id,
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

    /** @test */
    public function it_gets_a_collection_of_open_job_openings(): void
    {
        $company = Company::factory()->create();

        $jobOpeningA = JobOpening::factory()->create([
            'company_id' => $company->id,
            'team_id' => null,
        ]);
        $jobOpeningB = JobOpening::factory()->create([
            'company_id' => $company->id,
        ]);
        JobOpening::factory()->create([
            'company_id' => $company->id,
            'fulfilled' => true,
        ]);

        $array = DashboardHRJobOpeningsViewHelper::openJobOpenings($company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $jobOpeningA->id,
                    'title' => $jobOpeningA->title,
                    'reference_number' => $jobOpeningA->reference_number,
                    'active' => $jobOpeningA->active,
                    'team' => null,
                    'url' => env('APP_URL').'/'. $company->id.'/dashboard/hr/job-openings/'.$jobOpeningA->id,
                ],
                1 => [
                    'id' => $jobOpeningB->id,
                    'title' => $jobOpeningB->title,
                    'reference_number' => $jobOpeningB->reference_number,
                    'active' => $jobOpeningB->active,
                    'team' => [
                        'id' => $jobOpeningB->team->id,
                        'name' => $jobOpeningB->team->name,
                        'url' => env('APP_URL') . '/' . $company->id . '/teams/' . $jobOpeningB->team->id,
                    ],
                    'url' => env('APP_URL') . '/' . $company->id . '/dashboard/hr/job-openings/' . $jobOpeningB->id,
                ],
            ],
            $array['open_job_openings']->toArray()
        );

        $this->assertEquals(
            [
                'count' => 1,
                'url' => env('APP_URL') . '/' . $company->id . '/dashboard/hr/job-openings/fulfilled',
            ],
            $array['fulfilled_job_openings']
        );

        $this->assertEquals(
            env('APP_URL') . '/' . $company->id . '/dashboard/hr/job-openings/create',
            $array['url_create']
        );
    }

    /** @test */
    public function it_gets_the_detail_of_a_job_opening(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $company = Company::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'activated_at' => Carbon::now(),
        ]);

        $michael = Employee::factory()->create();
        $jobOpening->sponsors()->syncWithoutDetaching([$michael->id]);

        $array = DashboardHRJobOpeningsViewHelper::show($company, $jobOpening);

        $this->assertCount(
            12,
            $array
        );

        $this->assertEquals(
            $jobOpening->id,
            $array['id']
        );
        $this->assertEquals(
            $jobOpening->title,
            $array['title']
        );
        $this->assertEquals(
            StringHelper::parse($jobOpening->description),
            $array['description']
        );
        $this->assertEquals(
            $jobOpening->slug,
            $array['slug']
        );
        $this->assertEquals(
            $jobOpening->reference_number,
            $array['reference_number']
        );
        $this->assertEquals(
            $jobOpening->active,
            $array['active']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['activated_at']
        );
        $this->assertEquals(
            env('APP_URL') . '/jobs/' . $company->slug . '/jobs/' . $jobOpening->slug.'?ignore=true',
            $array['url_public_view']
        );
        $this->assertEquals(
            env('APP_URL') . '/' . $company->id . '/dashboard/hr/job-openings/' . $jobOpening->id.'/edit',
            $array['url_edit']
        );
        $this->assertEquals(
            [
                'id' => $jobOpening->position->id,
                'title' => $jobOpening->position->title,
                'count_employees' => 0,
                'url' => env('APP_URL') . '/' . $company->id . '/company/hr/positions/' . $jobOpening->position->id,
            ],
            $array['position']
        );
        $this->assertEquals(
            [
                'id' => $jobOpening->team->id,
                'name' => $jobOpening->team->name,
                'count' => 0,
                'url' => env('APP_URL') . '/' . $company->id . '/teams/' . $jobOpening->team->id,
            ],
            $array['team']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL') . '/' . $company->id . '/employees/' . $michael->id,
                ],
            ],
            $array['sponsors']->toArray()
        );
    }
}
