<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Cache;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        (new CreateAccount)->execute([
            'account_id' => auth()->user()->account->id,
            'author_id' => auth()->user()->id,
            'first_name' => $request->get('firstname'),
            'last_name' => $request->get('lastname'),
            'permission_level' => $request->get('permission_level'),
        ]);

        return redirect(tenant('/account/employees'));
    }
}
