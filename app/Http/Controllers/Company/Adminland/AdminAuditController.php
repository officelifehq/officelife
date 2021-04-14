<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminAuditLogViewHelper;

class AdminAuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $logs = $loggedCompany->logs()->with('author')->paginate(15);

        $logsCollection = AdminAuditLogViewHelper::index($logs, $loggedEmployee);

        return Inertia::render('Adminland/Audit/Index', [
            'logs' => $logsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($logs),
        ]);
    }
}
