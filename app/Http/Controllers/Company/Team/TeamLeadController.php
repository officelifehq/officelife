<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\SetTeamLead;
use App\Services\Company\Team\UnSetTeamLead;

class TeamLeadController extends Controller
{
    /**
     * Search all possible team leads for this team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $teamId)
    {
        $potentialTeamLeads = Employee::search(
            $request->input('searchTerm'),
            $companyId,
            10,
            'created_at desc'
        );

        $leads = collect([]);
        foreach ($potentialTeamLeads as $lead) {
            $leads->push([
                'id' => $lead->id,
                'name' => $lead->name,
            ]);
        }

        return response()->json([
            'data' => $leads,
        ], 200);
    }

    /**
     * Update the information about the team's lead.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $request->input('employeeId'),
        ];

        $lead = (new SetTeamLead)->execute($data);

        return response()->json([
            'data' => $lead->toObject(),
        ], 200);
    }

    /**
     * Remove the current team lead.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $teamLeadId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $teamLeadId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $request->input('employeeId'),
        ];

        $team = (new UnSetTeamLead)->execute($data);

        return response()->json([
            'data' => $team->toObject(),
        ], 200);
    }
}
