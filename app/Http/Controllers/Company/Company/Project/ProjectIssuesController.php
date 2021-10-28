<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class ProjectIssuesController extends Controller
{
    /**
     * Display the New issue view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function new(Request $request, int $companyId, int $projectId, int $boardId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        return Inertia::render('Company/Project/Boards/ProjectIssue/Create', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
