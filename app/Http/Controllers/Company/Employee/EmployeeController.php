<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class EmployeeController extends Controller
{
    /**
     * Display the detail of an employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = Cache::get('currentCompany');
        $employee = Employee::findOrFail($employeeId);

        return View::component('ShowCompanyEmployee', [
            'company' => $company,
            'user' => auth()->user()->isPartOfCompany($company),
            'employee' => new EmployeeResource($employee),
        ]);
    }
}
