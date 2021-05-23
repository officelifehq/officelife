<?php

namespace App\Http\Controllers\Company\Employee\Work;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Http\ViewHelpers\Employee\EmployeeWorkViewHelper;

class EmployeeWorkController extends Controller
{
    /**
     * Display the detail of an employee’s work panel.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->where('id', $employeeId)
                ->with('company')
                ->with('user')
                ->with('status')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // information about what the logged employee can do
        $permissions = EmployeeShowViewHelper::permissions($loggedEmployee, $employee);

        // worklogs
        $startOfWeek = Carbon::now()->setTimezone($loggedEmployee->timezone)->startOfWeek();
        $currentDay = Carbon::now()->setTimezone($loggedEmployee->timezone);
        $worklogsCollection = EmployeeWorkViewHelper::worklog($employee, $loggedEmployee, $startOfWeek, $currentDay);
        $weeks = EmployeeWorkViewHelper::weeks($loggedEmployee);

        // work from home
        $workFromHomeStats = EmployeeShowViewHelper::workFromHomeStats($employee);

        // all recent ships of this employee
        $ships = EmployeeShowViewHelper::recentShips($employee);

        // all projects of this employee
        $projects = EmployeeWorkViewHelper::projects($employee, $loggedCompany);

        // all groups of this employee
        $groups = EmployeeWorkViewHelper::groups($employee, $loggedCompany);

        // information about the employee that the logged employee consults, that depends on what the logged Employee has the right to see
        $employee = EmployeeShowViewHelper::informationAboutEmployee($employee, $permissions, $loggedEmployee);

        return Inertia::render('Employee/Work/Index', [
            'menu' => 'work',
            'employee' => $employee,
            'permissions' => $permissions,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'worklog' => $worklogsCollection,
            'weeks' => $weeks,
            'workFromHomes' => $workFromHomeStats,
            'ships' => $ships,
            'projects' => $projects,
            'groups' => $groups,
        ]);
    }

    /**
     * Display the detail of a worklog for a specific day.
     * If the day is null, we'll take the week given in parameter, and take the
     * Friday of this week.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param string $week
     * @param string $day
     * @return mixed
     */
    public function worklogDay(Request $request, int $companyId, int $employeeId, string $week, string $day = null)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $startOfWeek = Carbon::createFromFormat('Y-m-d', $week, $loggedEmployee->timezone);

        if (! $day) {
            $day = $startOfWeek->copy()->addDays(4);
        } else {
            $day = Carbon::createFromFormat('Y-m-d', $day, $loggedEmployee->timezone);
        }

        $worklog = EmployeeWorkViewHelper::worklog($employee, $loggedEmployee, $startOfWeek, $day);

        return response()->json([
            'data' => $worklog,
        ], 200);
    }
}
