<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Company\AddEmployeeToCompany;

class EmployeeController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Cache::get('currentCompany')->employees()->get();

        return view('company.employee.index')
            ->withEmployees($employees);
    }

    /**
     * Show the Create employee view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.employee.create');
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'permission_level' => $request->get('permission_level'),
            'send_invitation' => false,
        ];

        (new AddEmployeeToCompany)->execute($request);

        return redirect(tenant('/account/employees'));
    }
}
