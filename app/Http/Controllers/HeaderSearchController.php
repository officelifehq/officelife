<?php

namespace App\Http\Controllers;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Resources\Company\Team\Team as TeamResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class HeaderSearchController extends Controller
{
    /**
     * Perform search of an employee from the header.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function employees(Request $request)
    {
        $search = $request->get('searchTerm');
        $employees = Employee::search($search, InstanceHelper::getLoggedCompany()->id, 10, 'created_at desc');

        return EmployeeResource::collection($employees);
    }

    /**
     * Perform search of an team from the header.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function teams(Request $request)
    {
        $search = $request->get('searchTerm');
        $teams = Team::search($search, InstanceHelper::getLoggedCompany()->id, 10, 'created_at desc');

        return TeamResource::collection($teams);
    }
}
