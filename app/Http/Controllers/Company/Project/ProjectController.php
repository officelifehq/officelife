<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display the list of projets.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return Response
     */
    public function show(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the project messages.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return Response
     */
    public function messages(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Messages', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the project messages.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $messageId
     * @return Response
     */
    public function message(Request $request, int $companyId, int $projectId, int $messageId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Message', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the create new project form.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return Response
     */
    public function create(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
