<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\AuditLogCollection;

class AdminAuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $logs = $company->logs()->with('author')->paginate(15);

        $logsCollection = AuditLogCollection::prepare($logs);

        return Inertia::render('Adminland/Audit/Index', [
            'logs' => $logsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($logs),
        ]);
    }
}
