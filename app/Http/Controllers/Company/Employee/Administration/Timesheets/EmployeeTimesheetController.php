<?php

namespace App\Http\Controllers\Company\Employee\Administration\Timesheets;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeTimesheetViewHelper;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;

class EmployeeTimesheetController extends Controller
{
    /**
     * Show the employee's timesheets, for the current year.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return Redirector|RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $currentYear = intval(Carbon::now()->format('Y'));

        // using a subquery to greatly improve the speed of the query, as well
        // as reducing the number of hydrated models
        $timesheets = Timesheet::where('employee_id', $employee->id)
            ->whereYear('started_at', (string) $currentYear)
            ->addSelect([
                'duration' => TimeTrackingEntry::select(DB::raw('SUM(duration) as duration'))
                    ->whereColumn('timesheet_id', 'timesheets.id')
                    ->groupBy('timesheet_id'),
            ])
            ->get();

        $yearsWithTimesheets = EmployeeTimesheetViewHelper::yearsWithTimesheets($employee, $loggedCompany);
        $monthsWithTimesheets = EmployeeTimesheetViewHelper::monthsWithTimesheets($employee, $loggedCompany, $currentYear);
        $timesheets = EmployeeTimesheetViewHelper::timesheets($timesheets, $employee, $loggedCompany);

        return Inertia::render('Employee/Administration/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'timesheet' => $timesheets,
            'years' => $yearsWithTimesheets,
            'year' => $currentYear,
            'months' => $monthsWithTimesheets,
            'currentYear' => $currentYear,
            'currentMonth' => null,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the employee's worklogs page, for the given year.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $year
     * @return Redirector|RedirectResponse|Response
     */
    public function year(Request $request, int $companyId, int $employeeId, int $year)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // using a subquery to greatly improve the speed of the query, as well
        // as reducing the number of hydrated models
        $timesheets = Timesheet::where('employee_id', $employee->id)
            ->whereYear('started_at', (string) $year)
            ->addSelect([
                'duration' => TimeTrackingEntry::select(DB::raw('SUM(duration) as duration'))
                    ->whereColumn('timesheet_id', 'timesheets.id')
                    ->groupBy('timesheet_id'),
            ])
            ->get();

        $yearsWithTimesheets = EmployeeTimesheetViewHelper::yearsWithTimesheets($employee, $loggedCompany);
        $monthsWithTimesheets = EmployeeTimesheetViewHelper::monthsWithTimesheets($employee, $loggedCompany, $year);
        $timesheets = EmployeeTimesheetViewHelper::timesheets($timesheets, $employee, $loggedCompany);

        return Inertia::render('Employee/Administration/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'timesheet' => $timesheets,
            'years' => $yearsWithTimesheets,
            'year' => $year,
            'months' => $monthsWithTimesheets,
            'currentYear' => $year,
            'currentMonth' => null,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the employee's worklogs page, for the given month of the given year.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $year
     * @param int $month
     * @return Redirector|RedirectResponse|Response
     */
    public function month(Request $request, int $companyId, int $employeeId, int $year, int $month)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // using a subquery to greatly improve the speed of the query, as well
        // as reducing the number of hydrated models
        $timesheets = Timesheet::where('employee_id', $employee->id)
            ->whereYear('started_at', (string) $year)
            ->whereMonth('started_at', (string) $month)
            ->addSelect([
                'duration' => TimeTrackingEntry::select(DB::raw('SUM(duration) as duration'))
                ->whereColumn('timesheet_id', 'timesheets.id')
                ->groupBy('timesheet_id'),
            ])
            ->get();

        $yearsWithTimesheets = EmployeeTimesheetViewHelper::yearsWithTimesheets($employee, $loggedCompany);
        $monthsWithTimesheets = EmployeeTimesheetViewHelper::monthsWithTimesheets($employee, $loggedCompany, $year);
        $timesheets = EmployeeTimesheetViewHelper::timesheets($timesheets, $employee, $loggedCompany);

        return Inertia::render('Employee/Administration/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'timesheet' => $timesheets,
            'years' => $yearsWithTimesheets,
            'year' => $year,
            'months' => $monthsWithTimesheets,
            'currentYear' => $year,
            'currentMonth' => $month,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show a specific employee's timesheet.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $timesheetId
     * @return Redirector|RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $employeeId, int $timesheetId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $timesheet = Timesheet::where('company_id', $loggedCompany->id)
                ->where('employee_id', $employeeId)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return redirect('/home');
        }

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $timesheetInfo = DashboardTimesheetViewHelper::show($timesheet);
        $daysInHeader = DashboardTimesheetViewHelper::daysHeader($timesheet);
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($timesheet, $loggedEmployee);

        return Inertia::render('Employee/Administration/Timesheets/Show', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'daysHeader' => $daysInHeader,
            'timesheet' => $timesheetInfo,
            'approverInformation' => $approverInformation,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
