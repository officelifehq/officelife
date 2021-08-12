<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Collection;
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
        // stages reached by the candidate
        $candidateStages = $candidate->stages()->get();

        // all the stages of the job opening
        $stages = $jobOpening->template->stages()->get();
        $stagesCollection = collect();
        foreach ($stages as $stage) {
            // has the candidate reached this stage?
            $candidateStage = $candidateStages->filter(function ($candidateStage) use ($stage) {
                return $candidateStage->stage_position == $stage->position;
            })->first();

            $stagesCollection->push([
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
                'status' => $candidateStage ? $candidateStage->status : CandidateStage::STATUS_PENDING,
                'url' => route('dashboard.hr.candidates.stage.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                    'candidate' => $candidate,
                    'stage' => $stage,
                ]),
            ]);
        }

        return [
            'id' => $candidate->id,
            'name' => $candidate->name,
            'email' => $candidate->email,
            'rejected' => $candidate->rejected,
            'created_at' => DateHelper::formatDate($candidate->created_at),
            'stages' => $stagesCollection,
        ];
    }

    /**
     * Get the information about the other job openings the candidate might have
     * applied to over time.
     *
     * @param Company $company
     * @param Candidate $candidate
     * @return Collection|null
     */
    public static function otherJobOpenings(Company $company, Candidate $candidate): ?Collection
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

        return $otherJobOpeningsCollection;
    }

    /**
     * Get the information about a candidate.
     *
     * @param Company $company
     * @param Candidate $candidate
     * @param CandidateStage $stage
     * @return array|null
     */
    public static function stage(CandidateStage $stage): ?array
    {
        // has a decision been made at this stage?
        $decision = null;
        if ($stage->status != CandidateStage::STATUS_PENDING) {
            $decider = $stage->decider()->with('position')->first();

            $decision = [
                'decider' => $decider ? [
                        'id' => $decider->id,
                        'name' => $decider->name,
                        'avatar' => ImageHelper::getAvatar($decider, 32),
                        'position' => (! $decider->position) ? null : [
                            'id' => $decider->position->id,
                            'title' => $decider->position->title,
                        ],
                        'url' => route('employees.show', [
                            'company' => $decider->company,
                            'employee' => $decider,
                        ]),
                    ] : [
                        'id' => null,
                        'name' => $stage->decider_name,
                    ],
                'decided_at' => $stage->decided_at ? DateHelper::formatDate($stage->decided_at) : null,
            ];
        }

        return [
            'id' => $stage->id,
            'status' => $stage->status,
            'decision' => $decision,
        ];
    }

    /**
     * Determine the highest stage reached by the candidate.
     * @param  Candidate
     * @return CandidateStage
     */
    public static function determineHighestStage(Candidate $candidate): CandidateStage
    {
        if ($candidate->rejected) {
            return CandidateStage::where('candidate_id', $candidate->id)
                ->where('status', CandidateStage::STATUS_REJECTED)
                ->first();
        }

        $highestReachedStage = CandidateStage::where('candidate_id', $candidate->id)
            ->whereNull('decider_id')
            ->orderBy('stage_position', 'asc')
            ->first();

        if (! $highestReachedStage) {
            return CandidateStage::where('candidate_id', $candidate->id)
                ->orderBy('stage_position', 'desc')
                ->first();
        }

        return $highestReachedStage;
    }
}
