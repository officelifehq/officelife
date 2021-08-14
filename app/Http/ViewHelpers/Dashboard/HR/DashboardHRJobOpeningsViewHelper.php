<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;
use Illuminate\Support\Collection;
use App\Models\Company\CandidateStage;

class DashboardHRJobOpeningsViewHelper
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
                'url' => route('dashboard.hr.openings.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ]);
        }

        $countFulfilledJobOpenings = $company->jobOpenings()->where('fulfilled', true)->count();

        return [
            'url_create' => route('dashboard.hr.openings.create', [
                'company' => $company,
            ]),
            'open_job_openings' => $jobOpeningsCollection,
            'fulfilled_job_openings' => [
                'count' => $countFulfilledJobOpenings,
                'url' => route('dashboard.hr.openings.index.fulfilled', [
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

        $allCandidates = $jobOpening->candidates()
            ->where('rejected', false)
            ->with('stages')
            ->get();

        $candidatesSelectedCount = 0;
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
                    'url' => route('dashboard.hr.candidates.show', [
                        'company' => $company,
                        'jobOpening' => $jobOpening,
                        'candidate' => $candidate,
                    ]),
                ]);
            } else {
                $candidatesSelectedCount = $candidatesSelectedCount + 1;
            }
        }

        $rejectedCandidates = $jobOpening->candidates()
            ->where('application_completed', true)
            ->where('rejected', true)
            ->get()
            ->count();

        return [
            'id' => $jobOpening->id,
            'title' => $jobOpening->title,
            'description' => StringHelper::parse($jobOpening->description),
            'slug' => $jobOpening->slug,
            'reference_number' => $jobOpening->reference_number,
            'active' => $jobOpening->active,
            'activated_at' => $jobOpening->activated_at ? DateHelper::formatDate($jobOpening->activated_at) : null,
            'page_views' => $jobOpening->page_views,
            'position' => [
                'id' => $position->id,
                'title' => $position->title,
                'count_employees' => $position->employees()->notLocked()->count(),
                'url' => route('hr.positions.show', [
                    'company' => $company,
                    'position' => $position,
                ]),
            ],
            'sponsors' => $sponsorsCollection,
            'candidates' => [
                'to_sort' => $candidatesCollection,
                'rejected_count' => $rejectedCandidates,
                'selected_count' => $candidatesSelectedCount,
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
            'url_public_view' => route('jobs.company.show.incognito', [
                'company' => $company->slug,
                'job' => $jobOpening->slug,
            ]),
            'url_edit' => route('dashboard.hr.openings.edit', [
                'company' => $company,
                'jobOpening' => $jobOpening,
            ]),
        ];
    }
}
