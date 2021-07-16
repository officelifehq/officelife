<?php

namespace App\Http\ViewHelpers\Company\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;

class CompanyHRPositionShowViewHelper
{
    /**
     * Get the detail of a specific position.
     *
     * @param Company $company
     * @param Position $position
     * @return array
     */
    public static function show(Company $company, Position $position): array
    {
        $employees = $position->employees()->notLocked()
            ->with('positionHistoryEntries')
            ->with('teams')
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            // get all the teams associated with the employee
            $teamsCollection = collect([]);
            foreach ($employee->teams as $team) {
                $teamsCollection->push([
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => route('team.show', [
                        'company' => $company,
                        'team' => $team,
                    ]),
                ]);
            }

            // get the 'started at' date
            $startedAt = $employee->positionHistoryEntries()
                ->whereNull('ended_at')
                ->orderBy('id', 'desc')
                ->first()
                ->started_at;

            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 30),
                'started_at' => DateHelper::formatDate($startedAt),
                'pronoun_id' => $employee->pronoun_id,
                'teams' => $teamsCollection,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        // get the ratio of pronouns
        $uniqueIdsOfPronouns = $employeesCollection
            ->unique('pronoun_id')
            ->pluck('pronoun_id')
            ->values()
            ->all();

        $pronouns = Pronoun::addSelect([
            'number_of_employees' => Employee::selectRaw('count(*)')
                ->whereColumn('pronoun_id', 'pronouns.id')
                ->notLocked()
                ->where('company_id', $company->id),
        ])->whereIn('id', $uniqueIdsOfPronouns)->get();

        $pronounsCollection = collect([]);
        foreach ($pronouns as $pronoun) {
            $pronounsCollection->push([
                'id' => $pronoun->id,
                'label' => trans($pronoun->translation_key),
                'number_of_employees' => (int) $pronoun->number_of_employees,
                'percent' => (int) round($pronoun->number_of_employees * 100 / $employees->count(), 0),
            ]);
        }

        return [
            'position' => [
                'id' => $position->id,
                'title' => $position->title,
            ],
            'employees' => $employeesCollection,
            'pronouns' => $pronounsCollection,
        ];
    }
}
