<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\RecruitingStageTemplate;
use App\Services\Company\Project\CreateIssueType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Adminland\AdminRecruitmentViewHelper;
use App\Http\ViewHelpers\Adminland\AdminProjectManagementViewHelper;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStage;
use App\Services\Company\Adminland\JobOpening\UpdateRecruitingStage;
use App\Services\Company\Adminland\JobOpening\DestroyRecruitingStage;

class AdminProjectManagementController extends Controller
{
    /**
     * Show the project management page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $data = AdminProjectManagementViewHelper::issueTypes($company);

        return Inertia::render('Adminland/Project/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'data' => $data,
        ]);
    }

    /**
     * Create the issue type.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'icon_hex_color' => $request->input('hex_code'),
        ];

        $type = (new CreateIssueType)->execute($data);

        return response()->json([
            'data' => [
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
            ],
        ], 201);
    }

    /**
     * Show the template content.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $templateId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $templateId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $template = RecruitingStageTemplate::where('company_id', $company->id)
                ->findOrFail($templateId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $template = AdminRecruitmentViewHelper::show($company, $template);

        return Inertia::render('Adminland/Recruitment/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'template' => $template,
        ]);
    }

    /**
     * Create the stage.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $templateId
     * @return JsonResponse
     */
    public function storeStage(Request $request, int $companyId, int $templateId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'recruiting_stage_template_id' => $templateId,
            'name' => $request->input('name'),
        ];

        $stage = (new CreateRecruitingStage)->execute($data);

        return response()->json([
            'data' => [
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
            ],
        ], 201);
    }

    /**
     * Update the stage.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $templateId
     * @param int $stageId
     * @return JsonResponse
     */
    public function updateStage(Request $request, int $companyId, int $templateId, int $stageId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'recruiting_stage_template_id' => $templateId,
            'recruiting_stage_id' => $stageId,
            'name' => $request->input('name'),
            'position' => $request->input('position'),
        ];

        $stage = (new UpdateRecruitingStage)->execute($data);

        return response()->json([
            'data' => [
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
            ],
        ], 200);
    }

    /**
     * Delete the stage.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $templateId
     * @param int $stageId
     * @return JsonResponse
     */
    public function destroyStage(Request $request, int $companyId, int $templateId, int $stageId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'recruiting_stage_template_id' => $templateId,
            'recruiting_stage_id' => $stageId,
        ];

        (new DestroyRecruitingStage)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
