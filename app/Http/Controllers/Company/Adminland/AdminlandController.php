<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class AdminlandController extends Controller
{
    /**
     * Show the account dashboard.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        return Inertia::render('Adminland/Index', [
            'paidPlanEnabled' => config('officelife.enable_paid_plan'),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
