<?php

namespace App\Http\ViewHelpers\Company\HR;

use ErrorException;
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
            try {
                $startedAt = $employee->positionHistoryEntries()
                    ->whereNull('ended_at')
                    ->orderBy('id', 'desc')
                    ->first()
                    ->started_at;
            } catch (ErrorException $e) {
                $startedAt = null;
            }

            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 30),
                'started_at' => $startedAt ? DateHelper::formatDate($startedAt) : null,
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

        $pronouns = Pronoun::whereIn('id', $uniqueIdsOfPronouns)->get();

        $pronounsCollection = collect([]);
        foreach ($pronouns as $pronoun) {
            $employeesWithGivenPronoun = $employeesCollection->filter(function ($employee) use ($pronoun) {
                return $employee['pronoun_id'] == $pronoun->id;
            });

            $pronounsCollection->push([
                'id' => $pronoun->id,
                'label' => trans($pronoun->translation_key),
                'number_of_employees' => $employeesWithGivenPronoun->count(),
                'percent' => (int) round($employeesWithGivenPronoun->count() * 100 / $employees->count(), 0),
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
