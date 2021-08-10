<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CandidateStage;

class DashboardHRCandidatesViewHelper
{
    /**
     * Get the information about the job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @param Candidate $candidate
     * @return array|null
     */
    public static function jobOpening(Company $company, JobOpening $jobOpening): ?array
    {
        return [
            'id' => $jobOpening->id,
            'title' => $jobOpening->title,
            'description' => StringHelper::parse($jobOpening->description),
            'slug' => $jobOpening->slug,
            'reference_number' => $jobOpening->reference_number,
            'active' => $jobOpening->active,
            'activated_at' => $jobOpening->activated_at ? DateHelper::formatDate($jobOpening->activated_at) : null,
            'url' => route('dashboard.hr.openings.show', [
                'company' => $company,
                'jobOpening' => $jobOpening,
            ]),
        ];
    }

    /**
     * Get the information about a candidate.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @param Candidate $candidate
     * @return array|null
     */
    public static function candidate(Company $company, JobOpening $jobOpening, Candidate $candidate): ?array
    {
        // has the candidate applied to other job openings?
        $otherJobOpenings = DB::table('job_openings')
            ->join('candidates', 'job_openings.id', '=', 'candidates.job_opening_id')
            ->where('candidates.company_id', '=', $company->id)
            ->where('candidates.email', 'like', $candidate->email)
            ->select('job_openings.id', 'job_openings.title', 'job_openings.slug', 'job_openings.reference_number', 'job_openings.active', 'job_openings.fulfilled')
            ->get();

        $otherJobOpeningsCollection = collect();
        foreach ($otherJobOpenings as $otherJobOpening) {
            $otherJobOpeningsCollection->push([
                'id' => $otherJobOpening->id,
                'title' => $otherJobOpening->title,
                'slug' => $otherJobOpening->slug,
                'reference_number' => $otherJobOpening->reference_number,
                'active' => $otherJobOpening->active,
                'fulfilled' => $otherJobOpening->fulfilled,
            ]);
        }

        // stages reached by the candidate
        $candidateStages = $candidate->stages()->get();

        // all the stages of the job opening
        $stages = $jobOpening->template->stages()->get();
        $stagesCollection = collect();

        // add the first initial stage
        $status = CandidateStage::STATUS_PENDING;

        if ($candidateStages->count() > 0) {
            $status = CandidateStage::STATUS_PASSED;
        }

        if ($candidateStages->count() == 0 && $candidate->rejected) {
            $status = CandidateStage::STATUS_REJECTED;
        }

        $stagesCollection->push([
            'id' => 0,
            'name' => 'Initial selection',
            'position' => 0,
            'status' => $status,
        ]);

        // now add the other stages that are defined for this job opening
        foreach ($stages as $stage) {
            // has the candidate reached this stage?
            $candidateStage = $candidateStages->filter(function ($candidateStage) use ($stage) {
                return $candidateStage->stage_position == $stage->position;
            });

            $stagesCollection->push([
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
                'status' => $candidateStage->count() > 0 ? $candidateStage->status : CandidateStage::STATUS_PENDING,
            ]);
        }

        return [
            'id' => $candidate->id,
            'name' => $candidate->name,
            'email' => $candidate->email,
            'sorted' => $candidate->sorted,
            'rejected' => $candidate->rejected,
            'created_at' => DateHelper::formatDate($candidate->created_at),
            'other_job_openings' => $otherJobOpeningsCollection,
            'stages' => $stagesCollection,
        ];
    }
}
