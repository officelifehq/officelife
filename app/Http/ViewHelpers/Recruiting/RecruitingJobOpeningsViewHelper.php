<?php

namespace App\Http\ViewHelpers\Recruiting;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CandidateStage;

class RecruitingJobOpeningsViewHelper
{
    /**
     * Get all the positions in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function positions(Company $company): ?Collection
    {
        $positions = $company->positions()->orderBy('title')->get();

        $positionsCollection = collect();
        foreach ($positions as $position) {
            $positionsCollection->push([
                'value' => $position->id,
                'label' => $position->title,
            ]);
        }

        return $positionsCollection;
    }

    /**
     * Get all the recruiting templates in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function templates(Company $company): ?Collection
    {
        $templates = $company->recruitingStageTemplates()->orderBy('name')->get();

        $templatesCollection = collect();
        foreach ($templates as $template) {
            $templatesCollection->push([
                'value' => $template->id,
                'label' => $template->name,
            ]);
        }

        return $templatesCollection;
    }

    /**
     * Get all the potential sponsors in the company.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection|null
     */
    public static function potentialSponsors(Company $company, string $criteria): ?Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('email', 'LIKE', '%' . $criteria . '%');
            })
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
     * Get all the teams in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function teams(Company $company): ?Collection
    {
        $teams = $company->teams()->orderBy('name')->get();

        $teamsCollection = collect();
        foreach ($teams as $team) {
            $teamsCollection->push([
                'value' => $team->id,
                'label' => $team->name,
            ]);
        }

        return $teamsCollection;
    }

    /**
     * Get all the open job openings in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function openJobOpenings(Company $company): array
    {
        $openJobOpenings = $company->jobOpenings()
            ->with('team')
            ->with('position')
            ->with('sponsors')
            ->where('fulfilled', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $jobOpeningsCollection = collect();
        foreach ($openJobOpenings as $jobOpening) {
            $team = $jobOpening->team;

            $jobOpeningsCollection->push([
                'id' => $jobOpening->id,
                'title' => $jobOpening->title,
                'reference_number' => $jobOpening->reference_number,
                'active' => $jobOpening->active,
                'team' => $team ? [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => route('team.show', [
                        'company' => $company,
                        'team' => $team,
                    ]), ] : null,
                'url' => route('recruiting.openings.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ]);
        }

        $countFulfilledJobOpenings = $company->jobOpenings()->where('fulfilled', true)->count();

        return [
            'url_create' => route('recruiting.openings.create', [
                'company' => $company,
            ]),
            'open_job_openings' => $jobOpeningsCollection,
            'fulfilled_job_openings' => [
                'count' => $countFulfilledJobOpenings,
                'url' => route('recruiting.openings.index.fulfilled', [
                    'company' => $company,
                ]),
            ],
        ];
    }

    /**
     * Get all the fulfilled job openings in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function fulfilledJobOpenings(Company $company): array
    {
        $fulfilledJobOpenings = $company->jobOpenings()
            ->with('team')
            ->with('position')
            ->with('sponsors')
            ->where('fulfilled', true)
            ->orderBy('fulfilled_at', 'desc')
            ->get();

        $jobOpeningsCollection = collect();
        foreach ($fulfilledJobOpenings as $jobOpening) {
            $team = $jobOpening->team;

            $jobOpeningsCollection->push([
                'id' => $jobOpening->id,
                'title' => $jobOpening->title,
                'reference_number' => $jobOpening->reference_number,
                'fulfilled_at' => DateHelper::formatDate($jobOpening->fulfilled_at),
                'team' => $team ? [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => route('team.show', [
                        'company' => $company,
                        'team' => $team,
                    ]),
                ] : null,
                'url' => route('recruiting.openings.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ]);
        }

        $countOpenJobOpenings = $company->jobOpenings()->where('fulfilled', false)->count();

        return [
            'url_create' => route('recruiting.openings.create', [
                'company' => $company,
            ]),
            'fulfilled_job_openings' => $jobOpeningsCollection,
            'open_job_openings' => [
                'count' => $countOpenJobOpenings,
                'url' => route('recruiting.openings.index', [
                    'company' => $company,
                ]),
            ],
        ];
    }

    /**
     * Get all the details about a specific job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return array
     */
    public static function show(Company $company, JobOpening $jobOpening): array
    {
        $team = $jobOpening->team;
        $position = $jobOpening->position;

        $stagesCollection = collect();
        foreach ($jobOpening->template->stages as $stage) {
            $stagesCollection->push([
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
            ]);
        }

        return [
            'id' => $jobOpening->id,
            'title' => $jobOpening->title,
            'description_raw' => $jobOpening->description,
            'description' => StringHelper::parse($jobOpening->description),
            'slug' => $jobOpening->slug,
            'reference_number' => $jobOpening->reference_number,
            'active' => $jobOpening->active,
            'fulfilled' => $jobOpening->fulfilled,
            'activated_at' => $jobOpening->activated_at ? DateHelper::formatDate($jobOpening->activated_at) : null,
            'page_views' => $jobOpening->page_views,
            'recruiting_stage_template_id' => $jobOpening->template->id,
            'position' => [
                'id' => $position->id,
                'title' => $position->title,
                'count_employees' => $position->employees()->notLocked()->count(),
                'url' => route('hr.positions.show', [
                    'company' => $company,
                    'position' => $position,
                ]),
            ],
            'stages' => $stagesCollection,
            'team' => $team ? [
                'id' => $team->id,
                'name' => $team->name,
                'count' => $team->employees()->notLocked()->count(),
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ] : null,
            'employee' => $jobOpening->fulfilled ?
                ($jobOpening->candidateWhoWonTheJob->employee ? [
                    'id' => $jobOpening->candidateWhoWonTheJob->employee->id,
                    'name' => $jobOpening->candidateWhoWonTheJob->employee->name,
                    'avatar' => ImageHelper::getAvatar($jobOpening->candidateWhoWonTheJob->employee, 35),
                    'position' => (! $jobOpening->candidateWhoWonTheJob->employee->position) ? null : [
                        'id' => $jobOpening->candidateWhoWonTheJob->employee->position->id,
                        'title' => $jobOpening->candidateWhoWonTheJob->employee->position->title,
                    ],
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $jobOpening->candidateWhoWonTheJob->employee,
                    ]),
                ] : [
                    'name' => $jobOpening->candidateWhoWonTheJob->employee_name,
                ]) : null,
            'url_public_view' => route('jobs.company.show.incognito', [
                'company' => $company->slug,
                'job' => $jobOpening->slug,
            ]),
            'url_edit' => route('recruiting.openings.edit', [
                'company' => $company,
                'jobOpening' => $jobOpening,
            ]),
        ];
    }

    /**
     * Information needed to edit the job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return array
     */
    public static function edit(Company $company, JobOpening $jobOpening): array
    {
        $team = $jobOpening->team;
        $position = $jobOpening->position;

        $sponsorsCollection = collect([]);
        foreach ($jobOpening->sponsors as $sponsor) {
            $sponsorsCollection->push([
                'id' => $sponsor->id,
                'name' => $sponsor->name,
                'avatar' => ImageHelper::getAvatar($sponsor, 64),
            ]);
        }

        return [
            'id' => $jobOpening->id,
            'title' => $jobOpening->title,
            'description_raw' => $jobOpening->description,
            'reference_number' => $jobOpening->reference_number,
            'recruiting_stage_template_id' => $jobOpening->template->id,
            'sponsors' => $sponsorsCollection,
            'position' => [
                'id' => $position->id,
            ],
            'team' => $team ? [
                'id' => $team->id,
            ] : null,
            'url_cancel' => route('recruiting.openings.show', [
                'company' => $company,
                'jobOpening' => $jobOpening,
            ]),
        ];
    }

    /**
     * Get the stats about the job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return array
     */
    public static function stats(Company $company, JobOpening $jobOpening): array
    {
        $rejectedCandidatesCount = DB::table('candidates')
            ->where('job_opening_id', $jobOpening->id)
            ->where('application_completed', true)
            ->where('rejected', true)
            ->count();

        $allCandidates = $jobOpening->candidates()
            ->where('application_completed', true)
            ->where('rejected', false)
            ->with('stages')
            ->get();

        $candidatesToSortCount = 0;
        $candidatesSelectedCount = 0;
        foreach ($allCandidates as $candidate) {
            $needsToBeSorted = true;
            $candidateStages = $candidate->stages;
            foreach ($candidateStages as $candidateStage) {
                if ($candidateStage->status != CandidateStage::STATUS_PENDING) {
                    $needsToBeSorted = false;
                }
            }

            if ($needsToBeSorted) {
                $candidatesToSortCount = $candidatesToSortCount + 1;
            } else {
                $candidatesSelectedCount = $candidatesSelectedCount + 1;
            }
        }

        return [
            'to_sort' => [
                'count' => $candidatesToSortCount,
                'url' => route('recruiting.openings.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ],
            'selected' => [
                'count' => $candidatesSelectedCount,
                'url' => route('recruiting.openings.show.selected', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ],
            'rejected' => [
                'count' => $rejectedCandidatesCount,
                'url' => route('recruiting.openings.show.rejected', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ],
        ];
    }

    /**
     * Get all the sponsors about a specific job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return Collection
     */
    public static function sponsors(Company $company, JobOpening $jobOpening): Collection
    {
        $sponsors = $jobOpening->sponsors;
        $sponsorsCollection = collect();
        foreach ($sponsors as $sponsor) {
            $sponsorsCollection->push([
                'id' => $sponsor->id,
                'name' => $sponsor->name,
                'avatar' => ImageHelper::getAvatar($sponsor, 35),
                'position' => (! $sponsor->position) ? null : [
                    'id' => $sponsor->position->id,
                    'title' => $sponsor->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $sponsor,
                ]),
            ]);
        }

        return $sponsorsCollection;
    }

    /**
     * Get the candidates to sort for the given job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return Collection
     */
    public static function toSort(Company $company, JobOpening $jobOpening): Collection
    {
        $allCandidates = $jobOpening->candidates()
            ->where('rejected', false)
            ->where('application_completed', true)
            ->with('stages')
            ->get();

        $candidatesCollection = collect();
        foreach ($allCandidates as $candidate) {
            $needsToBeSorted = true;
            $candidateStages = $candidate->stages;
            foreach ($candidateStages as $candidateStage) {
                if ($candidateStage->status != CandidateStage::STATUS_PENDING) {
                    $needsToBeSorted = false;
                }
            }

            if ($needsToBeSorted) {
                $candidatesCollection->push([
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'received_at' => DateHelper::formatDate($candidate->created_at),
                    'url' => route('recruiting.candidates.show', [
                        'company' => $company,
                        'jobOpening' => $jobOpening,
                        'candidate' => $candidate,
                    ]),
                ]);
            }
        }

        return $candidatesCollection;
    }

    /**
     * Get the candidates who have been rejected for the given job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return Collection
     */
    public static function rejected(Company $company, JobOpening $jobOpening): Collection
    {
        $rejectedCandidates = $jobOpening->candidates()
            ->where('rejected', true)
            ->with('stages')
            ->get();

        $candidatesCollection = collect();
        foreach ($rejectedCandidates as $candidate) {
            $stageCollection = collect();
            foreach ($candidate->stages as $stage) {
                if ($stage->status != CandidateStage::STATUS_PENDING) {
                    $stageCollection->push([
                        'id' => $stage->id,
                        'name' => $stage->stage_name,
                        'position' => $stage->stage_position,
                        'status' => $stage->status,
                    ]);
                }
            }

            $candidatesCollection->push([
                'id' => $candidate->id,
                'name' => $candidate->name,
                'received_at' => DateHelper::formatDate($candidate->created_at),
                'stages' => $stageCollection,
                'url' => route('recruiting.candidates.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                    'candidate' => $candidate,
                ]),
            ]);
        }

        return $candidatesCollection;
    }

    /**
     * Get the candidates who have been selected for the given job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     * @return Collection
     */
    public static function selected(Company $company, JobOpening $jobOpening): Collection
    {
        $rejectedCandidates = $jobOpening->candidates()
            ->where('rejected', false)
            ->with('stages')
            ->get();

        $needsToBeSorted = true;
        $candidatesCollection = collect();
        foreach ($rejectedCandidates as $candidate) {
            $stageCollection = collect();
            foreach ($candidate->stages as $stage) {
                if ($stage->status != CandidateStage::STATUS_PENDING) {
                    $needsToBeSorted = false;
                }

                $stageCollection->push([
                    'id' => $stage->id,
                    'name' => $stage->stage_name,
                    'position' => $stage->stage_position,
                    'status' => $stage->status,
                ]);
            }

            if (! $needsToBeSorted) {
                $candidatesCollection->push([
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'received_at' => DateHelper::formatDate($candidate->created_at),
                    'stages' => $stageCollection,
                    'url' => route('recruiting.candidates.show', [
                        'company' => $company,
                        'jobOpening' => $jobOpening,
                        'candidate' => $candidate,
                    ]),
                ]);
            }
        }

        return $candidatesCollection;
    }
}
