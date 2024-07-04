<?php

namespace App\Http\Controllers\Company\Team;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\Links\CreateTeamUsefulLink;
use App\Services\Company\Team\Links\DestroyTeamUsefulLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamUsefulLinkController extends Controller
{
    /**
     * Add a new useful link to the team.
     */
    public function store(Request $request, int $companyId, int $teamId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'type' => $request->input('type'),
            'label' => ($request->input('label')) ? $request->input('label') : null,
            'url' => $request->input('url'),
        ];

        $link = (new CreateTeamUsefulLink)->execute($data);

        return response()->json([
            'data' => $link->toObject(),
        ], 200);
    }

    /**
     * Remove the new useful link from the team.
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $linkId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_useful_link_id' => $linkId,
        ];

        (new DestroyTeamUsefulLink)->execute($data);

        return response()->json([
            'data' => $linkId,
        ], 200);
    }
}
