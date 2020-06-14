<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\Ship\CreateShip;
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
     * Show the Post recent ship form.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $companyId, int $teamId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $employees = null;

        // create an array of ids of employees
        if ($request->input('employees')) {
            $employees = [];
            foreach ($request->input('employees') as $employee) {
                array_push($employees, $employee['id']);
            }
        }

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'employees' => $employees,
        ];

        (new CreateShip)->execute($request);

        return response()->json([
            'data' => true,
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
