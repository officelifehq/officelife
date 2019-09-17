<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class EmployeeEditController extends Controller
{
    /**
     * Show the employee edit page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = Employee::findOrFail($employeeId);

        try {
            $this->validateAccess(
                Auth::user()->id,
                $companyId,
                $employeeId,
                config('homas.authorizations.hr')
            );
        } catch (\Exception $e) {
            return redirect('/home');
        }

        return Inertia::render('Employee/Logs', [
            'employee' => new EmployeeResource($employee),
            'notifications' => Auth::user()->getLatestNotifications($company),
        ]);
    }
}
