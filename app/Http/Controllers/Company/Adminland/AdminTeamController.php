<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminTeamViewHelper;
use App\Models\Company\Team;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Services\Company\Adminland\Team\DestroyTeam;
use App\Services\Company\Adminland\Team\UpdateTeam;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminTeamController extends Controller
{
    /**
     * Show the list of teams.
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $teams = $company->teams()->with('company')->with('leader')->orderBy('name', 'asc')->get();
        $teamCollection = AdminTeamViewHelper::teams($teams);

        return Inertia::render('Adminland/Team/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'teams' => $teamCollection,
        ]);
    }

    /**
     * Create the team.
     */
    public function store(Request $request): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => InstanceHelper::getLoggedCompany()->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        $team = (new CreateTeam)->execute($data);

        return response()->json([
            'data' => AdminTeamViewHelper::team($team),
        ], 201);
    }

    /**
     * Update the name of the team.
     */
    public function update(Request $request, int $companyId, int $teamId): JsonResponse
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
            'data' => AdminTeamViewHelper::team($team),
        ], 200);
    }

    /**
     * Delete the team.
     */
    public function destroy(Request $request, int $companyId, int $teamId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
        ];

        (new DestroyTeam)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Display the logs page for the given team.
     *
     * @return mixed
     */
    public function logs(Request $request, int $companyId, int $teamId)
    {
        try {
            $team = Team::findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $logs = $team->logs()->with('author')->paginate(15);

        $logsCollection = AdminTeamViewHelper::logs($logs);

        return Inertia::render('Adminland/Team/Logs', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'logs' => $logsCollection,
            'paginator' => PaginatorHelper::getData($logs),
        ]);
    }
}
