<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\SetTeamLead;
use App\Services\Company\Team\UnsetTeamLead;
use App\Http\ViewHelpers\Team\TeamViewHelper;

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
            'created_at desc',
            'and locked = false',
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
                'avatar' => $lead->avatar,
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
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $teamLeadId)
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
            'data' => TeamViewHelper::team($team),
        ], 200);
    }
}
