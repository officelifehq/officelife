<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\SetTeamLead;
use App\Services\Company\Team\UnsetTeamLead;
use App\Http\ViewHelpers\Team\TeamShowViewHelper;

class TeamLeadController extends Controller
{
    /**
     * Search all possible team leads for this team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $teamId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = TeamShowViewHelper::searchPotentialLead($loggedCompany, $request->input('searchTerm'));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Update the information about the team's lead.
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

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $request->input('employeeId'),
        ];

        $lead = (new SetTeamLead)->execute($data);

        return response()->json([
            'data' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'avatar' => ImageHelper::getAvatar($lead, 35),
                'position' => (! $lead->position) ? null : [
                    'title' => $lead->position->title,
                ],
            ],
        ], 200);
    }

    /**
     * Remove the current team lead.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $teamLeadId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $teamLeadId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $request->input('employeeId'),
        ];

        $team = (new UnsetTeamLead)->execute($data);

        return response()->json([
            'data' => TeamShowViewHelper::team($team),
        ], 200);
    }
}
