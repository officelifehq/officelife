<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class AdminAuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $logs = $company->logs()->with('author')->paginate(15);

        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $logsCollection->push([
                'action' => $log->action,
                'objects' => json_decode($log->objects),
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($log->author) ? null : $log->author->id,
                    'name' => is_null($log->author) ? null : $log->author->name,
                ],
                'created_at' => $log->created_at,
            ]);
        }

        return Inertia::render('Adminland/Audit/Index', [
            'logs' => $logsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => [
                'count' => $logs->count(),
                'currentPage' => $logs->currentPage(),
                'firstItem' => $logs->firstItem(),
                'hasMorePages' => $logs->hasMorePages(),
                'lastItem' => $logs->lastItem(),
                'lastPage' => $logs->lastPage(),
                'nextPageUrl' => $logs->nextPageUrl(),
                'onFirstPage' => $logs->onFirstPage(),
                'perPage' => $logs->perPage(),
                'previousPageUrl' => $logs->previousPageUrl(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
