<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CreateUser;

class EmployeeController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = auth()->user()->account->users()->get();

        return view('account.employee.index')
            ->withEmployees($employees);
    }

    /**
     * Show the Create employee view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account.employee.create');
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        (new CreateUser)->execute([
            'account_id' => auth()->user()->account->id,
            'author_id' => auth()->user()->id,
            'first_name' => $request->get('firstname'),
            'last_name' => $request->get('lastname'),
            'permission_level' => $request->get('permission_level'),
        ]);

        return redirect(tenant('/account/employees'));
    }
}
