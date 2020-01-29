<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\TeamCollection;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Services\Company\Adminland\Team\UpdateTeam;

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

        $teams = $company->teams()->with('leader')->orderBy('name', 'asc')->get();
        $teamCollection = TeamCollection::prepare($teams);

        return Inertia::render('Adminland/Team/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'teams' => $teamCollection,
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
            'data' => $team->toObject(),
        ], 201);
    }

    /**
     * Update the name of the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $companyId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'name' => $request->input('name'),
        ];

        $team = (new UpdateTeam)->execute($data);

        return response()->json([
            'data' => $team->toObject(),
        ], 200);
    }
}
