<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminlandController extends Controller
{
    /**
     * Show the account dashboard.
     */
    public function index(Request $request, int $companyId): Response
    {
        return Inertia::render('Adminland/Index', [
            'paidPlanEnabled' => config('officelife.enable_paid_plan'),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
