<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class TeamController extends Controller
{
    /**
     * Display the detail of a team.
     *
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $companyId, $teamId)
    {
        $company = Cache::get('currentCompany');
        $team = Team::findOrFail($teamId);

        $employees = $team->employees()->orderBy('created_at', 'desc')->get();
        $employeeCount = $employees->count();
        $mostRecentEmployee = $employees->first();

        return View::component('ShowCompanyTeam', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'team' => new TeamResource($team),
            'employeeCount' => $employeeCount,
            'mostRecentEmployee' => new EmployeeResource($mostRecentEmployee),
            //'employees' => EmployeeResource::collection($employees),
        ]);
    }
}
