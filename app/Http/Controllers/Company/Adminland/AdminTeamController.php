<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
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
        $employee = Cache::get('cachedEmployeeObject');
        $teams = TeamResource::collection(
            $company->teams()->orderBy('name', 'desc')->get()
        );

        return Inertia::render('Adminland/Team/Index', [
            'company' => $company,
            'employee' => $employee,
            'notifications' => auth()->user()->getLatestNotifications($company),
            'teams' => $teams,
        ]);
    }

    /**
     * Show the Create team view.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('company.account.team.create');
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'name' => $request->get('name'),
        ];

        $team = (new CreateTeam)->execute($request);

        return response()->json([
            'data' => $team,
        ]);
    }
}
