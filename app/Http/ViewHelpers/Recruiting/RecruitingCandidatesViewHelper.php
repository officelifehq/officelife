<?php

namespace App\Http\ViewHelpers\Recruiting;

use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Collection;
use App\Models\Company\CandidateStage;

class RecruitingCandidatesViewHelper
{
    /**
     * Get the information about the job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return array|null
     */
    public static function jobOpening(Company $company, JobOpening $jobOpening): ?array
    {
        $team = $jobOpening->team;
        $position = $jobOpening->position;

        return [
            'id' => $jobOpening->id,
            'title' => $jobOpening->title,
            'description' => StringHelper::parse($jobOpening->description),
            'slug' => $jobOpening->slug,
            'reference_number' => $jobOpening->reference_number,
            'active' => $jobOpening->active,
            'fulfilled' => $jobOpening->fulfilled,
            'activated_at' => $jobOpening->activated_at ? DateHelper::formatDate($jobOpening->activated_at) : null,
            'position' => [
                'id' => $position->id,
                'title' => $position->title,
                'count_employees' => $position->employees()->notLocked()->count(),
                'url' => route('hr.positions.show', [
                    'company' => $company,
                    'position' => $position,
                ]),
            ],
            'team' => $team ? [
                'id' => $team->id,
                'name' => $team->name,
                'count' => $team->employees()->notLocked()->count(),
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ] : null,
            'url' => route('recruiting.openings.show', [
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
        $candidateStagesCollection = collect();
        foreach ($candidate->stages as $stage) {
            $candidateStagesCollection->push([
                'id' => $stage->id,
                'name' => $stage->stage_name,
                'position' => $stage->stage_position,
                'status' => $stage->status,
                'url' => route('recruiting.candidates.stage.show', [
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
            'stages' => $candidateStagesCollection,
            'url_hire' => route('recruiting.candidates.hire', [
                'company' => $company,
                'jobOpening' => $jobOpening,
                'candidate' => $candidate,
            ]),
            'url_stages' => route('recruiting.candidates.show', [
                'company' => $company,
                'jobOpening' => $jobOpening,
                'candidate' => $candidate,
            ]),
            'url_cv' => route('recruiting.candidates.cv', [
                'company' => $company,
                'jobOpening' => $jobOpening,
                'candidate' => $candidate,
            ]),
        ];
    }

    /**
     * Get the information about the other job openings the candidate might have
     * applied to over time.
     *
     * @param Company $company
     * @param Candidate $candidate
     * @param JobOpening $opening
     * @return Collection|null
     */
    public static function otherJobOpenings(Company $company, Candidate $candidate, JobOpening $opening): ?Collection
    {
        // has the candidate applied to other job openings?
        $otherCandidatesWithTheSameEmail = Candidate::where('company_id', $company->id)
                ->where('email', 'like', $candidate->email)
                ->where('job_opening_id', '!=', $opening->id)
                ->where('application_completed', true)
                ->with('jobOpening')
                ->select('job_opening_id')
                ->get();

        $otherJobOpeningsCollection = collect();
        foreach ($otherCandidatesWithTheSameEmail as $candidate) {
            $otherJobOpeningsCollection->push([
                'id' => $candidate->jobOpening->id,
                'title' => $candidate->jobOpening->title,
                'slug' => $candidate->jobOpening->slug,
                'reference_number' => $candidate->jobOpening->reference_number,
                'active' => $candidate->jobOpening->active,
                'fulfilled' => $candidate->jobOpening->fulfilled,
                'url' => route('recruiting.openings.show', [
                    'company' => $company,
                    'jobOpening' => $candidate->jobOpening,
                ]),
            ]);
        }

        return $otherJobOpeningsCollection;
    }

    /**
     * Get the information about a stage.
     *
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
     * Get the information about the participants in a stage.
     *
     * @param CandidateStage $stage
     * @return Collection|null
     */
    public static function participants(CandidateStage $stage): ?Collection
    {
        $participants = $stage->participants()->with('participant')->get();
        $participantCollection = collect();
        foreach ($participants as $participant) {
            $participantCollection->push([
                'id' => $participant->participant->id,
                'name' => $participant->participant->name,
                'avatar' => ImageHelper::getAvatar($participant->participant, 32),
                'participated' => $participant->participated,
                'participant_id' => $participant->id,
                'url' => route('employees.show', [
                    'company' => $participant->participant->company,
                    'employee' => $participant->participant,
                ]),
            ]);
        }

        return $participantCollection;
    }

    /**
     * Determine the highest stage reached by the candidate.
     *
     * @param Candidate $candidate
     * @return CandidateStage
     */
    public static function determineHighestStage(Candidate $candidate): CandidateStage
    {
        if ($candidate->rejected) {
            return CandidateStage::where('candidate_id', $candidate->id)
                ->where('status', CandidateStage::STATUS_REJECTED)
                ->orderBy('stage_position', 'asc')
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

    /**
     * Returns the potential employees that can be added as participants.
     * This filters out the current participants for the given stage.
     *
     * @param Company $company
     * @param CandidateStage $stage
     * @param string $criteria
     * @return Collection
     */
    public static function potentialParticipants(Company $company, CandidateStage $stage, string $criteria): Collection
    {
        $currentParticipants = $stage->participants()
            ->select('participant_id')
            ->pluck('participant_id');

        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('email', 'LIKE', '%' . $criteria . '%');
            })
            ->whereNotIn('id', $currentParticipants)
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $potentialEmployeesCollection = collect([]);
        foreach ($potentialEmployees as $potential) {
            $potentialEmployeesCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => ImageHelper::getAvatar($potential, 64),
            ]);
        }

        return $potentialEmployeesCollection;
    }

    /**
     * Get the information about the notes in a stage.
     *
     * @param Candidate $candidate
     * @return Collection|null
     */
    public static function documents(Candidate $candidate): ?Collection
    {
        return $candidate->files->map(function ($file) {
            return [
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
            ];
        });
    }

    /**
     * Get the information about the notes in a stage.
     *
     * @param Company $company
     * @param CandidateStage $stage
     * @param Employee $loggedEmployee
     * @return Collection|null
     */
    public static function notes(Company $company, CandidateStage $stage, Employee $loggedEmployee): ?Collection
    {
        $notes = $stage->notes()->with('author')
            ->orderBy('id', 'desc')
            ->get();

        $noteCollection = collect();
        foreach ($notes as $note) {
            $noteCollection->push([
                'id' => $note->id,
                'note' => $note->note,
                'parsed_note' => StringHelper::parse($note->note),
                'created_at' => DateHelper::formatShortDateWithTime($note->created_at),
                'author' => $note->author ? [
                    'id' => $note->author->id,
                    'name' => $note->author->name,
                    'avatar' => ImageHelper::getAvatar($note->author, 32),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $note->author,
                    ]),
                ] : [
                    'name' => $note->author_name,
                ],
                'permissions' => [
                    'can_edit' => $loggedEmployee->id === $note->author->id,
                    'can_destroy' => $loggedEmployee->id === $note->author->id,
                ],
            ]);
        }

        return $noteCollection;
    }
}
