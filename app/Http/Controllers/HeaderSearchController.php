<?php

namespace App\Http\Controllers;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class HeaderSearchController extends Controller
{
    /**
     * Perform search of an employee from the header.
     *
     * @return \Illuminate\Http\Response
     */
    public function employees(Request $request)
    {
        $search = $request->get('searchTerm');
        $employees = Employee::search($search, Cache::get('currentCompany')->id, 10, 'created_at desc');

        return EmployeeResource::collection($employees);
    }

    /**
     * Perform search of an team from the header.
     *
     * @return \Illuminate\Http\Response
     */
    public function teams(Request $request)
    {
        $search = $request->get('searchTerm');
        $teams = Team::search($search, Cache::get('currentCompany')->id, 10, 'created_at desc');

        return TeamResource::collection($teams);
    }
}
