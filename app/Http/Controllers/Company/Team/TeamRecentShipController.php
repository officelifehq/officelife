<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\Ship\CreateShip;
use App\Services\Company\Team\Ship\DestroyShip;
use App\Http\ViewHelpers\Team\TeamRecentShipViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamRecentShipController extends Controller
{
    /**
     * Show the Recent ships page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function index(Request $request, int $companyId, int $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $company->id)
                ->with('ships')
                ->with('ships.employees')
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $ships = TeamRecentShipViewHelper::recentShips($team);

        if ($ships->count() == 0) {
            return redirect('/home');
        }

        return Inertia::render('Team/Ship/Index', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
            'ships' => $ships,
        ]);
    }

    /**
     * Search an employee to add to the recent ship entry.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = TeamRecentShipViewHelper::search($loggedCompany, $request->input('searchTerm'));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Show the Create recent ship form.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
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
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $teamId): JsonResponse
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

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'employees' => $employees,
        ];

        (new CreateShip)->execute($data);

        return response()->json([
            'data' => true,
        ]);
    }

    /**
     * Show the details of a recent ship.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $recentShipId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function show(Request $request, int $companyId, int $teamId, int $recentShipId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $team = Team::where('company_id', $loggedCompany->id)
            ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $ship = Ship::where('team_id', $team->id)
                ->with('employees')
                ->findOrFail($recentShipId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $ship = TeamRecentShipViewHelper::ship($ship);

        return Inertia::render('Team/Ship/Show', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
            'userBelongsToTheTeam' => $loggedEmployee->isInTeam($teamId),
            'ship' => $ship,
        ]);
    }

    /**
     * Delete the recent ship.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $shipId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $shipId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'ship_id' => $shipId,
        ];

        (new DestroyShip)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
