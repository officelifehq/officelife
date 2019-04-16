<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class LogsController extends Controller
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
        $company = Cache::get('currentCompany');
        $employee = Employee::findOrFail($employeeId);

        try {
            $this->validateAccess(
                auth()->user()->id,
                $companyId,
                $employeeId,
                config('homas.authorizations.hr')
            );
        } catch (\Exception $e) {
            return redirect('/home');
        }

        $logs = $employee->employeeLogs()->paginate(15);

        $logsCollection = collect([]);
        $sentence = '';
        foreach ($logs->all() as $log) {
            if ($log->action == 'employee_created') {
                $sentence = $log->author.' created this employee entry.';
            }

            if ($log->action == 'manager_assigned') {
                $sentence = $log->author.' assigned '.$log->manager.' as a manager.';
            }

            if ($log->action == 'direct_report_assigned') {
                $sentence = $log->author.' assigned '.$log->directReport.' as a direct report.';
            }

            if ($log->action == 'manager_unassigned') {
                $sentence = $log->author.' removed '.$log->manager.' as a manager.';
            }

            if ($log->action == 'direct_report_unassigned') {
                $sentence = $log->author.' removed '.$log->directReport.' as a direct report.';
            }

            if ($log->action == 'position_assigned') {
                $sentence = $log->author.' assigned the position called '.$log->position.'.';
            }

            if ($log->action == 'position_removed') {
                $sentence = $log->author.' removed the position called '.$log->position.'.';
            }

            $logsCollection->push([
                'name' => $log->author,
                'sentence' => $sentence,
                'date' => $log->date,
            ]);
        }

        return View::component('ShowEmployeeLogs', [
            'company' => $company,
            'employee' => new EmployeeResource($employee),
            'logs' => $logsCollection,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
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
