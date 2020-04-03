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
use App\Http\Collections\WorkFromHomeCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Employee\EmployeeWorkFromHomeViewHelper;

class EmployeeWorkFromHomeController extends Controller
{
    /**
     * Show the employee's work from home page, for the current year.
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
     * Common page builder for the work from home page.
     */
    private function buildPage(Employee $employee, int $year, int $month = null)
    {
        $entries = $employee
            ->workFromHomes()
            ->with('employee')
            ->orderBy('employee_work_from_home.date')
            ->get();

        $yearsCollection = EmployeeWorkFromHomeViewHelper::yearsWithEntries($entries);
        $monthsCollection = EmployeeWorkFromHomeViewHelper::monthsWithEntries($entries, $year);

        // only select work from home for the current year
        $entries = $entries->filter(function ($log) use ($year) {
            return $log->date->year === $year;
        });

        // filter by month, if necessary
        if ($month) {
            $entries = $entries->filter(function ($log) use ($year, $month) {
                return $log->date->year === $year &&
                    $log->date->month === $month;
            });
        }

        return Inertia::render('Employee/WorkFromHome/Index', [
            'employee' => $employee->toObject(),
            'entries' => WorkFromHomeCollection::prepare($entries),
            'years' => $yearsCollection,
            'year' => $year,
            'months' => $monthsCollection,
            'currentYear' => $year,
            'currentMonth' => ($month) ? $month : null,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
