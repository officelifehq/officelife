<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Http\Resources\Company\Team\Team as TeamResource;

class AdminTeamController extends Controller
{
    /**
     * Show the list of teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $teams = TeamResource::collection(
            $company->teams()->orderBy('name', 'desc')->get()
        );

        return Inertia::render('Adminland/Team/Index', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'teams' => $teams,
        ]);
    }

    /**
     * Create the team.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => InstanceHelper::getLoggedCompany()->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->get('name'),
        ];

        $team = (new CreateTeam)->execute($request);

        return response()->json([
            'data' => $team,
        ]);
    }
}
