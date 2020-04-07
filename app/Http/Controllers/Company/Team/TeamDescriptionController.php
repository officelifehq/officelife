<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\Description\SetTeamDescription;

class TeamDescriptionController extends Controller
{
    /**
    * Update the information about the team's description.
    *
    * @param Request $request
    * @param int $companyId
    * @param int $teamId
    *
    * @return JsonResponse
    */
    public function store(Request $request, $companyId, $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'description' => $request->input('description'),
        ];

        $team = (new SetTeamDescription)->execute($data);

        return response()->json([
            'data' => $team->toObject(),
        ], 200);
    }
}
