<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\User\Preferences\UpdateDashboardView;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardHRController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('cachedCompanyObject');

        (new UpdateDashboardView)->execute([
            'user_id' => auth()->user()->id,
            'company_id' => $company->id,
            'view' => 'hr',
        ]);

        return View::component('ShowCompany', [
            'company' => $company,
            'user' => auth()->user()->refresh(),
            'employee' => new EmployeeResource(auth()->user()->getEmployeeObjectForCompany($company)),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
