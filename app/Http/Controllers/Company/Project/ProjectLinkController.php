<?php

namespace App\Http\Controllers\Company\Project;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProjectLink;
use App\Services\Company\Project\DestroyProjectLink;

class ProjectLinkController extends Controller
{
    /**
     * Create a new project link.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'type' => $request->input('type'),
            'label' => ($request->input('label')) ? $request->input('label') : null,
            'url' => $request->input('url'),
        ];

        $link = (new CreateProjectLink)->execute($data);

        return response()->json([
            'data' => [
                'id' => $link->id,
                'type' => $link->type,
                'label' => $link->label,
                'url' => $link->url,
            ],
        ], 201);
    }

    /**
     * Destroy a new project link.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $linkId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $linkId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_link_id' => $linkId,
        ];

        (new DestroyProjectLink)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
