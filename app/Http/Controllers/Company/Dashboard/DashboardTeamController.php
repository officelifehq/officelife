<?php

namespace App\Http\Controllers\Company\Dashboard;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\Collections\TeamCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Dashboard\DashboardTeamViewHelper;

class DashboardTeamController extends Controller
{
    /**
     * Displays the Team page on the dashboard.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param mixed $requestedDate
     *
     * @return Response
     */
    public function index(Request $request, int $companyId, int $teamId = null, $requestedDate = null): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();
        $teams = $employee->teams()->with('employees')->get();

        $employeeInformation = [
            'id' => $employee->id,
            'has_logged_worklog_today' => $employee->hasAlreadyLoggedWorklogToday(),
            'has_logged_morale_today' => $employee->hasAlreadyLoggedMoraleToday(),
            'dashboard_view' => 'team',
        ];

        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'team',
        ])->onQueue('low');

        // if there are no teams, display a blank state
        if ($teams->count() == 0) {
            return Inertia::render('Dashboard/Team/Partials/MyTeamEmptyState', [
                'company' => $company,
                'employee' => $employeeInformation,
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
                return Inertia::render('Dashboard/Team/Partials/MyTeamEmptyState', [
                    'company' => $company,
                    'employee' => $employeeInformation,
                    'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
                    'message' => trans('dashboard.team_not_allowed'),
                ]);
            }

            $exists = $teams->contains($teamId);
            if (! $exists) {
                return Inertia::render('Dashboard/Team/Partials/MyTeamEmptyState', [
                    'company' => $company,
                    'employee' => $employeeInformation,
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

        // building the collection containing the days with the worklogs
        // by default, the view should display the following days
        // Last Fri/M/T/W/T/F
        $dates = collect([]);
        $lastFriday = $requestedDate->copy()->startOfWeek()->subDays(3);
        $dates->push(DashboardTeamViewHelper::worklogs($team, $lastFriday));
        for ($i = 0; $i < 5; $i++) {
            $day = $requestedDate->copy()->startOfWeek()->addDays($i);
            $dates->push(DashboardTeamViewHelper::worklogs($team, $day));
        }

        // upcoming birthdays
        $birthdays = DashboardTeamViewHelper::birthdays($team);

        // who is working from home today
        $workFromHomes = DashboardTeamViewHelper::workFromHome($team);

        return Inertia::render('Dashboard/Team/Index', [
            'company' => $company,
            'employee' => $employeeInformation,
            'teams' => TeamCollection::prepare($teams),
            'currentTeam' => $team->id,
            'worklogDates' => $dates,
            'currentDate' => $requestedDate->format('Y-m-d'),
            'worklogEntries' => $team->worklogsForDate($requestedDate),
            'birthdays' => $birthdays,
            'workFromHomes' => $workFromHomes,
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
     *
     * @return \Illuminate\Http\Response
     */
    public function worklogDetails(Request $request, int $companyId, int $teamId, $requestedDate)
    {
        $company = InstanceHelper::getLoggedCompany();
        $requestedDate = Carbon::parse($requestedDate);
        $team = Team::where('company_id', $company->id)
            ->where('id', $teamId)
            ->firstOrFail();

        return response()->json([
            'worklogEntries' => $team->worklogsForDate($requestedDate),
            'currentDate' => $requestedDate->format('Y-m-d'),
        ]);
    }
}
