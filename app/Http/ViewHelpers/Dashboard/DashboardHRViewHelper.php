<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Timesheet;
use Illuminate\Support\Facades\DB;

class DashboardHRViewHelper
{
    /**
     * Get the list of pending validation timesheets for employees who don't
     * have managers, before the current week.
     *
     * @param Company $company
     * @return array
     */
    public static function employeesWithoutManagersWithPendingTimesheets(Company $company): array
    {
        // all the unapproved timesheets of employees without managers
        // except for the current week
        // this query is tricky and i don’t know how to do it efficiently with
        // eloquent. so the way to go is to fetch the timesheets that are ready
        // to submit first, then get all the employees with managers, then
        // remove all those employees from the list of timesheets. that will get
        // us all the timesheets for employees who don’t have managers.
        $timesheets = Timesheet::where('company_id', $company->id)
            ->where('status', Timesheet::READY_TO_SUBMIT)
            ->with('employee')
            ->whereDate('started_at', '<', Carbon::now()->startOfWeek(Carbon::MONDAY))
            ->get();

        // get the list of employees with manager, that we flatten
        /** @phpstan-ignore-next-line */
        $listOfEmployeesWithManagers = DB::table('direct_reports')
            ->where('company_id', $company->id)
            ->select('employee_id')
            ->pluck('employee_id');

        $timesheetsWithUniqueEmployees = $timesheets->unique('employee_id');
        $timesheetsWithUniqueEmployees = $timesheetsWithUniqueEmployees->whereNotIn('employee_id', $listOfEmployeesWithManagers);
        $timesheetsLeft = $timesheets->whereNotIn('employee_id', $listOfEmployeesWithManagers);

        $employeesCollection = collect([]);
        foreach ($timesheetsWithUniqueEmployees as $timesheet) {
            $employee = $timesheet->employee;

            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee),
            ]);
        }

        return [
            'number_of_timesheets' => $timesheetsLeft->count(),
            'employees' => $employeesCollection,
            'url_view_all' => route('dashboard.hr.timesheet.index', [
                'company' => $company,
            ]),
        ];
    }

    public static function statisticsAboutTimesheets(Company $company)
    {
        $now = Carbon::now();
        $totals = DB::table('timesheets')
            ->whereDate('started_at', '>=', $now->copy()->startOfWeek(Carbon::MONDAY)->subDays(30))
            ->whereDate('started_at', '<', $now->copy()->startOfWeek(Carbon::MONDAY))
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when status = '".Timesheet::REJECTED."' then 1 end) as rejected")
            ->first();

        return [
            'total' => $totals->total,
            'rejected' => $totals->rejected,
        ];
    }

    /**
     * Get information about the discipline cases.
     */
    public static function disciplineCases(Company $company)
    {
        return [
            'url' => [
                'index' => route('dashboard.hr.disciplinecase.index', [
                    'company' => $company,
                ]),
            ],
        ];
    }
}
