<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

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
}
