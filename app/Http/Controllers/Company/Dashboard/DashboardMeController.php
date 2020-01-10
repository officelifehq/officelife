<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UpdateDashboardPreference;

class DashboardMeController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        UpdateDashboardPreference::dispatch([
            'user_id' => Auth::user()->id,
            'company_id' => $company->id,
            'view' => 'me',
        ])->onQueue('low');

        $worklogCount = $employee->worklogs()->count();
        $moraleCount =  $employee->morales()->count();

        $employee = [
            'id' => $employee->id,
            'has_logged_worklog_today' => $employee->hasAlreadyLoggedWorklogToday(),
            'has_logged_morale_today' => $employee->hasAlreadyLoggedMoraleToday(),
        ];

        return Inertia::render('Dashboard/Me', [
            'employee' => $employee,
            'worklogCount' => $worklogCount,
            'moraleCount' => $moraleCount,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ownerPermissionLevel' => config('officelife.authorizations.administrator'),
        ]);
    }
}
