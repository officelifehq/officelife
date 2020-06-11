<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\TeamNews\CreateTeamNews;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamRecentShipController extends Controller
{
    /**
     * Show the Create recent ship form.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, int $companyId, int $teamId)
    {
        try {
            $team = Team::where('company_id', $companyId)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Team/Ship/Create', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
        ]);
    }

    /**
     * Show the Post team news form.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $companyId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new CreateTeamNews)->execute($request);

        return response()->json([
            'data' => $news->toObject(),
        ]);
    }

    /**
     * Search an employee to add as a team lead.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, int $companyId)
    {
        $potentialEmployees = Employee::search(
            $request->input('searchTerm'),
            $companyId,
            10,
            'created_at desc',
            'and locked = false',
        );

        $employees = collect([]);
        foreach ($potentialEmployees as $employee) {
            $employees->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
            ]);
        }

        return response()->json([
            'data' => $employees,
        ], 200);
    }
}
