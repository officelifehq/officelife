<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
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
     *
     * @return \Inertia\Response
     */
    public function index(Request $request, int $companyId)
    {
        return Inertia::render('Adminland/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
