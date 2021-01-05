<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\SQLHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
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

        return $this->buildPage($employee, $currentYear);
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
        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return $this->buildPage($employee, $year);
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
        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return $this->buildPage($employee, $year, $month);
    }

    /**
     * Common page builder for the timesheet page.
     *
     * @param Employee $employee
     * @param int $year
     * @param int|null $month
     * @return Response
     */
    private function buildPage(Employee $employee, int $year, int $month = null)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $entries = DB::table(DB::raw('timesheets, employees, time_tracking_entries'))
            ->select(DB::raw('employees.id as employee_id, '.SQLHelper::concat('employees.first_name', 'employees.last_name').' as name, timesheets.started_at, timesheets.ended_at, timesheets.id as id, timesheets.status, sum(time_tracking_entries.duration) as duration'))
            ->whereRaw('timesheets.company_id = '.$loggedCompany->id)
            ->whereRaw('timesheets.employee_id = '.$employee->id)
            ->whereRaw('timesheets.employee_id = employees.id')
            ->whereRaw('time_tracking_entries.timesheet_id = timesheets.id')
            ->groupBy('timesheets.id')
            ->orderBy('timesheets.started_at')
            ->get();

        $yearsWithTimesheets = EmployeeTimesheetViewHelper::yearsWithTimesheets($entries, $employee, $loggedCompany);
        $monthsWithTimesheets = EmployeeTimesheetViewHelper::monthsWithTimesheets($entries, $employee, $loggedCompany, $year);

        // only select entries for the current year
        $entries = $entries->filter(function ($timesheet) use ($year) {
            $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at);

            return $startedAt->year === $year;
        });

        // filter by month, if necessary
        if ($month) {
            $entries = $entries->filter(function ($timesheet) use ($year, $month) {
                $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at);

                return $startedAt->year === $year &&
                    $startedAt->month === $month;
            });
        }

        $entries = EmployeeTimesheetViewHelper::timesheets($entries, $employee, $employee->company);

        return Inertia::render('Employee/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'timesheet' => $entries,
            'years' => $yearsWithTimesheets,
            'year' => $year,
            'months' => $monthsWithTimesheets,
            'currentYear' => $year,
            'currentMonth' => ($month) ? $month : null,
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
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($timesheet);

        return Inertia::render('Employee/Timesheets/Show', [
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
