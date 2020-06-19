<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\WorklogHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;

class EmployeeShowViewHelper
{
    /**
     * Collection containing all the managers of this employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function managers(Employee $employee): Collection
    {
        $managers = $employee->managers;
        $managersOfEmployee = collect([]);
        foreach ($managers as $manager) {
            $manager = $manager->manager;

            $managersOfEmployee->push([
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => $manager->avatar,
                'position' => (! $manager->position) ? null : [
                    'id' => $manager->position->id,
                    'title' => $manager->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $manager,
                ]),
            ]);
        }

        return $managersOfEmployee;
    }

    /**
     * Collection containing all the direct reports of this employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function directReports(Employee $employee): Collection
    {
        $directReports = $employee->directReports()->with('directReport.position')->get();
        $directReportsOfEmployee = collect([]);
        foreach ($directReports as $directReport) {
            $directReport = $directReport->directReport;

            $directReportsOfEmployee->push([
                'id' => $directReport->id,
                'name' => $directReport->name,
                'avatar' => $directReport->avatar,
                'position' => (! $directReport->position) ? null : [
                    'id' => $directReport->position->id,
                    'title' => $directReport->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $directReport,
                ]),
            ]);
        }

        return $directReportsOfEmployee;
    }

    /**
     * Array containing a collection of all worklogs with the morale and a
     * link to the detailled page of the worklogs.
     *
     * @param Employee $employee
     *
     * @return array
     */
    public static function worklogs(Employee $employee): array
    {
        $worklogs = $employee->worklogs()->latest()->take(7)->get();
        $morales = $employee->morales()->latest()->take(7)->get();
        $worklogsCollection = collect([]);
        $currentDate = Carbon::now();

        // worklogs from Monday to Friday of the current week
        for ($i = 0; $i < 5; $i++) {
            $day = $currentDate->copy()->startOfWeek()->addDays($i);

            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $morale = $morales->first(function ($morale) use ($day) {
                return $morale->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $worklogsCollection->push(
                WorklogHelper::getDailyInformationForEmployee($day, $worklog, $morale)
            );
        }

        $array = [
            'worklogs_collection' => $worklogsCollection,
            'url' => route('employees.worklogs', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];

        return $array;
    }

    /**
     * Array containing information about the number of times the employee has
     * been working from home this year.
     *
     * @param Employee $employee
     *
     * @return array
     */
    public static function workFromHomeStats(Employee $employee): array
    {
        $workFromHomes = $employee->workFromHomes;

        // get all entries in the current year
        $entries = $workFromHomes->filter(function ($entry) {
            return $entry->date->isCurrentYear();
        });

        return [
            'work_from_home_today' => WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now()),
            'number_times_this_year' => $entries->count(),
            'url' => route('employees.workfromhome', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];
    }

    /**
     * Array containing information about the questions answered by the
     * employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function questions(Employee $employee): Collection
    {
        $answers = $employee->answers()->with('question')->get();

        $questionsCollection = collect([]);
        foreach ($answers as $answer) {
            $question = $answer->question;

            $questionsCollection->push([
                'id' => $question->id,
                'title' => $question->title,
                'url' => route('company.questions.show', [
                    'company' => $employee->company,
                    'question' => $question,
                ]),
                'answer' => [
                    'body' => $answer->body,
                ],
            ]);
        }

        return $questionsCollection;
    }

    /**
     * Array containing information about the teams.
     *
     * @param Employee $employee
     * @param Collection $teams
     *
     * @return Collection
     */
    public static function teams(Collection $teams, Employee $employee): Collection
    {
        // reduce the number of queries that the foreach loop generates
        // we don't need to iterate over this over and over as it'll be the same
        //for all those companies
        $company = $employee->company;

        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'team_leader' => is_null($team->leader) ? null : [
                    'id' => $team->leader->id,
                ],
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ]);
        }

        return $teamsCollection;
    }

    /**
     * Array containing information about the hardware associated with the
     * employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function hardware(Employee $employee): Collection
    {
        $hardware = $employee->hardware;

        $hardwareCollection = collect([]);
        foreach ($hardware as $item) {
            $hardwareCollection->push([
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
            ]);
        }

        return $hardwareCollection;
    }

    /**
     * Array containing information about the recent ships associated with the
     * employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function recentShips(Employee $employee): Collection
    {
        $ships = $employee->ships;

        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $employees = $ship->employees;
            $employeeCollection = collect([]);
            foreach ($employees as $employeeImpacted) {
                if ($employee->id == $employeeImpacted->id) {
                    continue;
                }

                $employeeCollection->push([
                    'id' => $employeeImpacted->id,
                    'name' => $employeeImpacted->name,
                    'avatar' => $employeeImpacted->avatar,
                    'url' => route('employees.show', [
                        'company' => $employee->company,
                        'employee' => $employeeImpacted,
                    ]),
                ]);
            }

            $shipsCollection->push([
                'id' => $ship->id,
                'title' => $ship->title,
                'description' => $ship->description,
                'employees' => ($employeeCollection->count() > 0) ? $employeeCollection->all() : null,
                'url' => route('ships.show', [
                    'company' => $employee->company,
                    'team' => $ship->team,
                    'ship' => $ship->id,
                ]),
            ]);
        }

        return $shipsCollection;
    }
}
