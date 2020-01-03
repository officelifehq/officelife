<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;
use App\Http\Resources\Company\EmployeeLog\EmployeeLog as EmployeeLogResource;

class EmployeeLogsController extends Controller
{
    /**
     * Show the employee log.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->findOrFail($employeeId);
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

        $logs = $employee->employeeLogs()->paginate(15);

        $logs = EmployeeLogResource::collection($logs);

        return Inertia::render('Employee/Logs', [
            'employee' => new EmployeeResource($employee),
            'logs' => $logs,
            'notifications' => Auth::user()->getLatestNotifications($company),
            'paginator' => [
                'count' => $logs->count(),
                'currentPage' => $logs->currentPage(),
                'firstItem' => $logs->firstItem(),
                'hasMorePages' => $logs->hasMorePages(),
                'lastItem' => $logs->lastItem(),
                'lastPage' => $logs->lastPage(),
                'nextPageUrl' => $logs->nextPageUrl(),
                'onFirstPage' => $logs->onFirstPage(),
                'perPage' => $logs->perPage(),
                'previousPageUrl' => $logs->previousPageUrl(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
