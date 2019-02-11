<?php

namespace App\Http\Controllers\Company;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $team = Team::findOrFail($teamId);

        return view('company.team.show')
            ->withTeam($team);
    }
}
