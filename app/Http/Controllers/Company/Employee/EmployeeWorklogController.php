<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\WorklogHelper;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Collections\WorklogCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeWorklogController extends Controller
{
    /**
     * Show the employee's worklogs page, for the current year.
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
            $this->validateAccess(
                Auth::user()->id,
                $companyId,
                $employeeId,
                config('officelife.authorizations.hr')
            );
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
            $this->validateAccess(
                Auth::user()->id,
                $companyId,
                $employeeId,
                config('officelife.authorizations.hr')
            );
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
            $this->validateAccess(
                Auth::user()->id,
                $companyId,
                $employeeId,
                config('officelife.authorizations.hr')
            );
        } catch (\Exception $e) {
            return redirect('/home');
        }

        return $this->buildPage($employee, $year, $month);
    }

    /**
     * Common page builder for the worklog page.
     */
    private function buildPage(Employee $employee, int $year, int $month = null)
    {
        $worklogs = $employee
            ->worklogs()
            ->with('employee')
            ->orderBy('worklogs.created_at')
            ->get();

        $yearsCollection = WorklogHelper::getYears($worklogs);
        $monthsCollection = WorklogHelper::getMonths($worklogs, $year);
        $graphDataCollection = WorklogHelper::getYearlyCalendar($worklogs, $year);

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
