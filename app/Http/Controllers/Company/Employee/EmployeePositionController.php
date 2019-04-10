<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Position\Position as PositionResource;

class EmployeePositionController extends Controller
{
    /**
     * Return a list of available titles.
     *
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $companyId)
    {
        $company = Cache::get('currentCompany');

        $titles = $company->titles;

        return PositionResource::collection($titles);
    }
}
