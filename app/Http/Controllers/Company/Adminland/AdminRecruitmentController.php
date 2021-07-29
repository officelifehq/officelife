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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Adminland\AdminRecruitmentViewHelper;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStageTemplate;

class AdminRecruitmentController extends Controller
{
    /**
     * Show the recruitment page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $templates = AdminRecruitmentViewHelper::index($company);

        return Inertia::render('Adminland/Recruitment/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'templates' => $templates,
        ]);
    }

    /**
     * Create the template.
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
        ];

        $template = (new CreateRecruitingStageTemplate)->execute($data);

        return response()->json([
            'data' => [
                'id' => $template->id,
                'name' => $template->name,
                'stages' => null,
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
}
