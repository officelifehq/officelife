<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Collections\EmployeeLogCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeLogsController extends Controller
{
    /**
     * Show the employee log.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
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
        $logsCollection = EmployeeLogCollection::prepare($logs);

        return Inertia::render('Employee/Logs/Index', [
            'employee' => $employee->toObject(),
            'logs' => $logsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($logs),
        ]);
    }
}
