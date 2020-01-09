<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\Preferences\UpdateDashboardView;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardCompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        (new UpdateDashboardView)->execute([
            'user_id' => Auth::user()->id,
            'company_id' => $company->id,
            'view' => 'company',
        ]);

        return Inertia::render('ShowCompany', [
            'company' => $company,
            'user' => Auth::user()->refresh(),
            'employee' => new EmployeeResource(Auth::user()->getEmployeeObjectForCompany($company)),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ownerPermissionLevel' => config('officelife.authorizations.administrator'),
        ]);
    }
}
