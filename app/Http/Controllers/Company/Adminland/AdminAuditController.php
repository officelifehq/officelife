<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\AuditLog\AuditLog as AuditLogResource;

class AdminAuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('cachedCompanyObject');
        $logs = $company->logs()->paginate(15);

        $logs = AuditLogResource::collection($logs);

        return Inertia::render('Adminland/Audit/Index', [
            'logs' => $logs,
            'notifications' => auth()->user()->getLatestNotifications($company),
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
