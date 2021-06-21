<?php

namespace App\Http\Controllers\Company\Dashboard\Teams;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Worklog\DestroyWorklog;
use App\Http\ViewHelpers\Dashboard\DashboardTeamViewHelper;

class DashboardTeamController extends Controller
{
    /**
     * Displays the Team page on the dashboard.
     *
     * @param Request $request
     * @param int $companyId
     * @param int|null $teamId
     * @param mixed $requestedDate
     * @return mixed
     */
    public function index(Request $request, int $companyId, int $teamId = null, $requestedDate = null)
    {
        if (! is_null($teamId)) {
            try {
                $team = Team::where('company_id', $companyId)
                    ->where('id', $teamId)
                    ->with('employees')
                    ->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect('home');
            }
        }

        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $teams = $loggedEmployee->teams()->with('employees')->with('ships')->get();

        UpdateDashboardPreference::dispatch([
            'employee_id' => $loggedEmployee->id,
            'company_id' => $company->id,
            'view' => 'team',
        ])->onQueue('low');

        // if there are no teams, display a blank state
        if ($teams->count() == 0) {
            return Inertia::render('Dashboard/Team/Partials/MyTeamEmptyState', [
                'company' => $company,
                'employee' => DashboardViewHelper::information($loggedEmployee, 'team'),
                'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
                'message' => trans('dashboard.blank_state'),
            ]);
        }

        // we display one team at a time. We need to check if a team has been
        // passed as a parameter. If not, we look for the first team the employee
        // is in.
        if (! is_null($teamId)) {
            try {
                $team = Team::where('company_id', $company->id)
                    ->where('id', $teamId)
                    ->with('employees')
                    ->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect('home');
            }

            $exists = $teams->contains($teamId);
            if (! $exists) {
                return Inertia::render('Dashboard/Team/Partials/MyTeamEmptyState', [
                    'company' => $company,
                    'employee' => DashboardViewHelper::information($loggedEmployee, 'team'),
                    'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
                    'message' => trans('dashboard.team_not_allowed'),
                ]);
            }
        }

        // the team is null at this stage, that means the URL didn't contain
        // a team ID, but the employee still is associated with at least one team
        if (! isset($team)) {
            $team = $teams->first();
        }

        // check if a specific date was required
        if (! is_null($requestedDate)) {
            $requestedDate = Carbon::parse($requestedDate);
        } else {
            $requestedDate = Carbon::now();
        }

        // upcoming birthdays
        $birthdays = DashboardTeamViewHelper::birthdays($team);

        // who is working from home today
        $workFromHomes = DashboardTeamViewHelper::workFromHome($team);

        // teams
        $teams = DashboardTeamViewHelper::teams($teams);

        // ships
        $ships = DashboardTeamViewHelper::ships($team);

        // upcoming new hires
        $newHires = DashboardTeamViewHelper::upcomingNewHires($team);

        // work logs
        $worklogs = DashboardTeamViewHelper::worklogsForTheLast7Days($team, $requestedDate, $loggedEmployee);

        $hiringDateAnniversaries = DashboardTeamViewHelper::upcomingHiredDateAnniversaries($team);

        return Inertia::render('Dashboard/Team/Index', [
            'company' => $company,
            'employee' => DashboardViewHelper::information($loggedEmployee, 'team'),
            'teams' => $teams,
            'currentTeam' => $team->id,
            'worklogDates' => $worklogs,
            'currentDate' => $requestedDate->format('Y-m-d'),
            'worklogEntries' => $team->worklogsForDate($requestedDate, $loggedEmployee),
            'birthdays' => $birthdays,
            'workFromHomes' => $workFromHomes,
            'recentShips' => $ships,
            'newHires' => $newHires,
            'hiringDateAnniversaries' => $hiringDateAnniversaries,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Displays the details of the worklogs for a given date.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param mixed $requestedDate
     * @return JsonResponse
     */
    public function worklogDetails(Request $request, int $companyId, int $teamId, $requestedDate): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $requestedDate = Carbon::parse($requestedDate);
        $team = Team::where('company_id', $company->id)
            ->where('id', $teamId)
            ->firstOrFail();

        return response()->json([
            'worklogEntries' => $team->worklogsForDate($requestedDate, $loggedEmployee),
            'currentDate' => $requestedDate->format('Y-m-d'),
        ]);
    }

    /**
     * Delete the worklog.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $worklogId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function destroyWorkLog(Request $request, int $companyId, int $teamId, int $worklogId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'worklog_id' => $worklogId,
        ];

        (new DestroyWorklog)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
