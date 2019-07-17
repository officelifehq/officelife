<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\User\Preferences\UpdateDashboardView;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardTeamController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('currentCompany');

        (new UpdateDashboardView)->execute([
            'user_id' => auth()->user()->id,
            'company_id' => $company->id,
            'view' => 'team',
        ]);

        return View::component('ShowCompany', [
            'company' => $company,
            'user' => auth()->user()->refresh(),
            'employee' => new EmployeeResource(auth()->user()->getEmployeeObjectForCompany($company)),
            'notifications' => auth()->user()->notifications->where('read', false)->take(5),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
