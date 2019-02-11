<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display the detail of an employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $companyId, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        return view('company.employee.show')
            ->withEmployee($employee);
    }
}
