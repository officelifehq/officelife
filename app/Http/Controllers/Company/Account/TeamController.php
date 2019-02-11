<?php

namespace App\Http\Controllers\Company\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Team\CreateTeam;

class TeamController extends Controller
{
    /**
     * Show the list of teams.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = Cache::get('currentCompany')->teams()->get();

        return view('company.account.team.index')
            ->withTeams($teams);
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

        (new CreateTeam)->execute($request);

        return redirect(tenant('/account/teams'));
    }
}
