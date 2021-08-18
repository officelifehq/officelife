<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use App\Models\Company\CandidateStage;
use App\Models\Company\RecruitingStage;
use App\Models\Company\CandidateStageNote;
use App\Models\Company\RecruitingStageTemplate;
use App\Models\Company\CandidateStageParticipant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRCandidatesViewHelper;

class DashboardHRCandidatesViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_details_about_the_job_opening(): void
    {
        $company = Company::factory()->create();

        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'title' => 'title',
            'description' => 'this is a test',
            'reference_number' => '123',
            'activated_at' => null,
        ]);

        $array = DashboardHRCandidatesViewHelper::jobOpening($company, $opening);

        $this->assertEquals(
            [
                'id' => $opening->id,
                'title' => 'title',
                'description' => '<p>this is a test</p>',
                'slug' => 'title',
                'reference_number' => '123',
                'active' => true,
                'activated_at' => null,
                'url' => env('APP_URL') . '/' . $company->id. '/dashboard/hr/job-openings/' . $opening->id,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_details_about_the_candidate(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $company = Company::factory()->create();

        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $company->id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);
        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
            'recruiting_stage_template_id' => $template->id,
        ]);
        $candidate = Candidate::factory()->create([
            'job_opening_id' => $opening->id,
            'name' => 'jean valjean',
            'email' => 'email',
            'url' => 'url',
            'notes' => 'notes',
            'uuid' => '1234',
        ]);

        $stage1 = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
            'status' => CandidateStage::STATUS_PASSED,
        ]);
        $stage2 = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 2,
            'status' => CandidateStage::STATUS_PENDING,
        ]);

        $array = DashboardHRCandidatesViewHelper::candidate($company, $opening, $candidate);

        $this->assertEquals(
            6,
            count($array)
        );

        $this->assertEquals(
            $candidate->id,
            $array['id']
        );
        $this->assertEquals(
            'jean valjean',
            $array['name']
        );
        $this->assertEquals(
            'email',
            $array['email']
        );
        $this->assertEquals(
            false,
            $array['rejected']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['created_at']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['created_at']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $stage1->id,
                    'name' => $stage1->stage_name,
                    'position' => $stage1->stage_position,
                    'status' => CandidateStage::STATUS_PASSED,
                    'url' => env('APP_URL') . '/' . $company->id. '/dashboard/hr/job-openings/' . $opening->id.'/candidates/'.$candidate->id.'/stages/'.$stage1->id,
                ],
                1 => [
                    'id' => $stage2->id,
                    'name' => $stage2->stage_name,
                    'position' => $stage2->stage_position,
                    'status' => CandidateStage::STATUS_PENDING,
                    'url' => env('APP_URL') . '/' . $company->id. '/dashboard/hr/job-openings/' . $opening->id.'/candidates/'.$candidate->id.'/stages/'.$stage2->id,
                ],
            ],
            $array['stages']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_other_job_openings_by_the_same_candidate(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();

        $opening = JobOpening::factory()->create([
            'company_id' => $company->id,
        ]);
        $candidate = Candidate::factory()->create([
            'job_opening_id' => $opening->id,
            'company_id' => $company->id,
            'email' => 'email',
            'application_completed' => true,
        ]);
        $collection = DashboardHRCandidatesViewHelper::otherJobOpenings($company, $candidate, $opening);
        $this->assertEquals(0, $collection->count());

        $opening2 = JobOpening::factory()->create([
            'company_id' => $company->id,
            'activated_at' => Carbon::now(),
        ]);
        Candidate::factory()->create([
            'job_opening_id' => $opening2->id,
            'company_id' => $company->id,
            'email' => 'email',
            'application_completed' => true,
        ]);
        Candidate::factory()->create([
            'job_opening_id' => $opening2->id,
            'company_id' => $company->id,
            'email' => 'email2',
            'application_completed' => true,
        ]);

        $collection = DashboardHRCandidatesViewHelper::otherJobOpenings($company, $candidate, $opening);

        $this->assertEquals(
            [
                0 => [
                    'id' => $opening2->id,
                    'title' => $opening2->title,
                    'slug' => $opening2->slug,
                    'reference_number' => $opening2->reference_number,
                    'active' => $opening2->active,
                    'fulfilled' => $opening2->fulfilled,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_details_about_the_candidate_stage(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $stage = CandidateStage::factory()->create([
            'decider_name' => 'alexis',
            'status' => CandidateStage::STATUS_PENDING,
        ]);
        $array = DashboardHRCandidatesViewHelper::stage($stage);
        $this->assertEquals(
            [
                'id' => $stage->id,
                'status' => CandidateStage::STATUS_PENDING,
                'decision' => null,
            ],
            $array
        );

        $stage = CandidateStage::factory()->create([
            'decider_name' => 'alexis',
            'decided_at' => Carbon::now(),
            'status' => CandidateStage::STATUS_PASSED,
        ]);
        $array = DashboardHRCandidatesViewHelper::stage($stage);
        $this->assertCount(
            3,
            $array,
        );
        $this->assertEquals(
            $stage->id,
            $array['id'],
        );
        $this->assertEquals(
            CandidateStage::STATUS_PASSED,
            $array['status'],
        );
        $this->assertEquals(
            [
                'decider' => [
                    'id' => $stage->decider->id,
                    'name' => $stage->decider->name,
                    'avatar' => ImageHelper::getAvatar($stage->decider, 32),
                    'position' => [
                        'id' => $stage->decider->position->id,
                        'title' => $stage->decider->position->title,
                    ],
                    'url' =>  env('APP_URL') . '/' . $stage->decider->company_id . '/employees/' . $stage->decider->id,
                ],
                'decided_at' => 'Jan 01, 2018',
            ],
            $array['decision'],
        );
    }

    /** @test */
    public function it_gets_the_details_about_the_participants(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $stage = CandidateStage::factory()->create();
        $candidateStageParticipant = CandidateStageParticipant::factory()->create([
            'candidate_stage_id' => $stage->id,
            'participant_id' => $jim->id,
            'participated' => true,
        ]);

        $collection = DashboardHRCandidatesViewHelper::participants($stage);
        $this->assertEquals(
            [
                0 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim, 32),
                    'participated' => true,
                    'participant_id' => $candidateStageParticipant->id,
                    'url' =>  env('APP_URL') . '/' . $jim->company_id. '/employees/' . $jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_potential_new_participants(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $jean = Employee::factory()->create([
            'first_name' => 'jean',
            'company_id' => $michael->company_id,
        ]);
        $stage = CandidateStage::factory()->create();
        CandidateStageParticipant::factory()->create([
            'candidate_stage_id' => $stage->id,
            'participant_id' => $michael->id,
        ]);
        CandidateStageParticipant::factory()->create([
            'candidate_stage_id' => $stage->id,
            'participant_id' => $jim->id,
        ]);

        $collection = DashboardHRCandidatesViewHelper::potentialParticipants($michael->company, $stage, 'je');
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

        $collection = DashboardHRCandidatesViewHelper::potentialParticipants($michael->company, $stage, 'roger');
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_highest_stage_reached_by_a_candidate(): void
    {
        $candidate = Candidate::factory()->create([
            'rejected' => true,
        ]);

        $stage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
            'status' => CandidateStage::STATUS_PASSED,
            'decider_id' => null,
        ]);
        $stage2 = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 2,
            'status' => CandidateStage::STATUS_REJECTED,
            'decider_id' => null,
        ]);

        $stageResult = DashboardHRCandidatesViewHelper::determineHighestStage($candidate);

        $this->assertInstanceOf(
            CandidateStage::class,
            $stageResult
        );
        $this->assertEquals(
            $stage2->id,
            $stageResult->id
        );

        $candidate->rejected = false;
        $candidate->save();

        $stageResult = DashboardHRCandidatesViewHelper::determineHighestStage($candidate);
        $this->assertEquals(
            $stage->id,
            $stageResult->id
        );
    }

    /** @test */
    public function it_gets_the_list_of_potential_participants(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'michael',
        ]);
        $jim = Employee::factory()->create([
            'company_id' => $michael->company_id,
            'first_name' => 'jim',
        ]);
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
            'first_name' => 'dwight',
        ]);

        $candidate = Candidate::factory()->create();
        $stage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
        ]);
        CandidateStageParticipant::factory()->create([
            'candidate_stage_id' => $stage->id,
            'participant_id' => $michael->id,
        ]);

        $collection = DashboardHRCandidatesViewHelper::potentialParticipants($michael->company, $stage, 'michael');
        $this->assertEquals(
            0,
            $collection->count()
        );

        $collection = DashboardHRCandidatesViewHelper::potentialParticipants($michael->company, $stage, 'jim');
        $this->assertEquals(
            [
                0 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim, 64),
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_notes(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $stage = CandidateStage::factory()->create();
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $stage->id,
            'author_id' => $michael->id,
        ]);
        $candidateStageNoteB = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $stage->id,
        ]);

        $collection = DashboardHRCandidatesViewHelper::notes($michael->company, $stage, $michael);
        $this->assertEquals(
            [
                0 => [
                    'id' => $candidateStageNoteB->id,
                    'note' => $candidateStageNoteB->note,
                    'parsed_note' => StringHelper::parse($candidateStageNoteB->note),
                    'created_at' => DateHelper::formatShortDateWithTime($candidateStageNoteB->created_at),
                    'author' => [
                        'id' => $candidateStageNoteB->author->id,
                        'name' => $candidateStageNoteB->author->name,
                        'avatar' => ImageHelper::getAvatar($candidateStageNoteB->author, 32),
                        'url' =>  env('APP_URL') . '/' . $michael->company_id . '/employees/' . $candidateStageNoteB->author->id,
                    ],
                    'permissions' => [
                        'can_edit' => false,
                        'can_destroy' => false,
                    ],
                ],
                1 => [
                    'id' => $candidateStageNote->id,
                    'note' => $candidateStageNote->note,
                    'parsed_note' => StringHelper::parse($candidateStageNote->note),
                    'created_at' => DateHelper::formatShortDateWithTime($candidateStageNote->created_at),
                    'author' => [
                        'id' => $candidateStageNote->author->id,
                        'name' => $candidateStageNote->author->name,
                        'avatar' => ImageHelper::getAvatar($michael, 32),
                        'url' =>  env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
                    ],
                    'permissions' => [
                        'can_edit' => true,
                        'can_destroy' => true,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }
}
