<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\Preferences\UpdateDashboardView;

class DashboardHRController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();

        (new UpdateDashboardView)->execute([
            'user_id' => Auth::user()->id,
            'company_id' => $company->id,
            'view' => 'hr',
        ]);

        return Inertia::render('Employee/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ownerPermissionLevel' => config('officelife.authorizations.administrator'),
        ]);
    }
}
