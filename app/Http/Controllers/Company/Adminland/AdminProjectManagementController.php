<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminProjectManagementViewHelper;
use App\Services\Company\Project\CreateIssueType;
use App\Services\Company\Project\DestroyIssueType;
use App\Services\Company\Project\UpdateIssueType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminProjectManagementController extends Controller
{
    /**
     * Show the project management page.
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
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'icon_hex_color' => $request->input('icon_hex_color'),
        ];

        $type = (new CreateIssueType)->execute($data);

        return response()->json([
            'data' => [
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
                'url' => [
                    'update' => route('projectmanagement.update', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                    'destroy' => route('projectmanagement.destroy', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                ],
            ],
        ], 201);
    }

    /**
     * Update the issue typpe.
     */
    public function update(Request $request, int $companyId, int $issueTypeId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'issue_type_id' => $issueTypeId,
            'name' => $request->input('name'),
            'icon_hex_color' => $request->input('icon_hex_color'),
        ];

        $type = (new UpdateIssueType)->execute($data);

        return response()->json([
            'data' => [
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
                'url' => [
                    'update' => route('projectmanagement.update', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                    'destroy' => route('projectmanagement.destroy', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                ],
            ],
        ], 200);
    }

    /**
     * Delete the issue type.
     */
    public function destroy(Request $request, int $companyId, int $issueTypeId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'issue_type_id' => $issueTypeId,
        ];

        (new DestroyIssueType)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
