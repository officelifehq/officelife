<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Collections\WorklogCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Employee\EmployeeWorklogViewHelper;

class EmployeeWorklogController extends Controller
{
    /**
     * Show the employee's worklogs page, for the current year.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $this->asUser(Auth::user())
                ->forEmployee($employee)
                ->forCompanyId($companyId)
                ->asPermissionLevel(config('officelife.permission_level.hr'))
                ->canAccessCurrentPage();
        } catch (\Exception $e) {
            return redirect('/home');
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
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
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

        try {
            $this->asUser(Auth::user())
                ->forEmployee($employee)
                ->forCompanyId($companyId)
                ->asPermissionLevel(config('officelife.permission_level.hr'))
                ->canAccessCurrentPage();
        } catch (\Exception $e) {
            return redirect('/home');
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
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
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

        try {
            $this->asUser(Auth::user())
                ->forEmployee($employee)
                ->forCompanyId($companyId)
                ->asPermissionLevel(config('officelife.permission_level.hr'))
                ->canAccessCurrentPage();
        } catch (\Exception $e) {
            return redirect('/home');
        }

        return $this->buildPage($employee, $year, $month);
    }

    /**
     * Common page builder for the worklog page.
     *
     * @param Employee $employee
     * @param int $year
     * @param int|null $month
     *
     * @return \Inertia\Response
     */
    private function buildPage(Employee $employee, int $year, int $month = null)
    {
        $worklogs = $employee
            ->worklogs()
            ->with('employee')
            ->orderBy('worklogs.created_at')
            ->get();

        $yearsCollection = EmployeeWorklogViewHelper::yearsWithEntries($worklogs);
        $monthsCollection = EmployeeWorklogViewHelper::monthsWithEntries($worklogs, $year);
        $graphDataCollection = EmployeeWorklogViewHelper::dataForYearlyCalendar($worklogs, $year);

        // only select worklogs for the current year
        $worklogs = $worklogs->filter(function ($log) use ($year) {
            return $log->created_at->year === $year;
        });

        // filter by month, if necessary
        if ($month) {
            $worklogs = $worklogs->filter(function ($log) use ($year, $month) {
                return $log->created_at->year === $year &&
                    $log->created_at->month === $month;
            });
        }

        return Inertia::render('Employee/Worklogs/Index', [
            'employee' => $employee->toObject(),
            'worklogs' => WorklogCollection::prepare($worklogs),
            'years' => $yearsCollection,
            'year' => $year,
            'months' => $monthsCollection,
            'currentYear' => $year,
            'currentMonth' => ($month) ? $month : null,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'graphData' => $graphDataCollection,
        ]);
    }
}
