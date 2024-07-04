<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeLogViewHelper;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EmployeeLogsController extends Controller
{
    /**
     * Show the employee log.
     *
     *
     * @return \Inertia\Response|Redirector|RedirectResponse
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        try {
            $employee = Employee::where('company_id', $companyId)
                ->findOrFail($employeeId);
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

        // logs
        $logs = $employee->employeeLogs()->with('author')->paginate(15);
        $logsCollection = EmployeeLogViewHelper::list($logs, $employee->company);

        return Inertia::render('Employee/Logs/Index', [
            'employee' => EmployeeLogViewHelper::employee($employee),
            'logs' => $logsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($logs),
        ]);
    }
}
