<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
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
        $company = Cache::get('cachedCompanyObject');
        $teams = TeamResource::collection(
            $company->teams()->orderBy('name', 'desc')->get()
        );

        return Inertia::render('Adminland/Team/Index', [
            'notifications' => auth()->user()->getLatestNotifications($company),
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
        $request = [
            'company_id' => Cache::get('cachedCompanyObject')->id,
            'author_id' => auth()->user()->id,
            'name' => $request->get('name'),
        ];

        $team = (new CreateTeam)->execute($request);

        return response()->json([
            'data' => $team,
        ]);
    }
}
