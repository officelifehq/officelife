<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Company\DirectReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\RejectTimesheet;
use App\Services\Company\Employee\Timesheet\ApproveTimesheet;

class DashboardTimesheetManagerController extends Controller
{
    /**
     * Approve the timesheet.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     * @return JsonResponse
     */
    public function approve(Request $request, int $companyId, int $timesheetId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $timesheet->employee->id,
            'timesheet_id' => $timesheetId,
        ];

        $timesheet = (new ApproveTimesheet)->execute($data);

        return response()->json([
            'data' => $timesheet->id,
        ], 201);
    }

    /**
     * Reject the timesheet.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     * @return JsonResponse
     */
    public function reject(Request $request, int $companyId, int $timesheetId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $timesheet->employee->id,
            'timesheet_id' => $timesheetId,
        ];

        $timesheet = (new RejectTimesheet)->execute($data);

        return response()->json([
            'data' => $timesheet->id,
        ], 201);
    }

    /**
     * Check that the current employee has access to this method.
     * @param Company $company
     * @param int $timesheetId
     * @param Employee $employee
     * @return mixed
     */
    private function canAccess(Company $company, int $timesheetId, Employee $employee)
    {
        try {
            $timesheet = Timesheet::where('company_id', $company->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($timesheet->status !== Timesheet::READY_TO_SUBMIT) {
            return redirect('home');
        }

        // is the user a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.timesheets')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        // can the manager see this timesheet?
        if (! $employee->isManagerOf($timesheet->employee->id)) {
            return redirect('home');
        }

        return $timesheet;
    }
}
