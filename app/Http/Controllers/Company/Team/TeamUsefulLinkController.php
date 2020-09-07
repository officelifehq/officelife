<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\Links\CreateTeamUsefulLink;
use App\Services\Company\Team\Links\DestroyTeamUsefulLink;

class TeamUsefulLinkController extends Controller
{
    /**
     * Add a new useful link to the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @return JsonResponse
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
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $linkId
     * @return JsonResponse
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
