<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;
use App\Http\Resources\Company\TeamNews\TeamNews as TeamNewsResource;

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
        $company = InstanceHelper::getLoggedCompany();
        $team = Team::findOrFail($teamId);

        $employees = $team->employees()->orderBy('created_at', 'desc')->get();
        $news = $team->news()->orderBy('created_at', 'desc')->limit(3)->get();
        $newsCount = $team->news()->count();
        $employeeCount = $employees->count();
        $mostRecentEmployee = $employees->first();

        return Inertia::render('Team/Show', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'team' => new TeamResource($team),
            'news' => TeamNewsResource::collection($news),
            'newsCount' => $newsCount,
            'employeeCount' => $employeeCount,
            'employees' => EmployeeResource::collection($employees),
        ]);
    }
}
