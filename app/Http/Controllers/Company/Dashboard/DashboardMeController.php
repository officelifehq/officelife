<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\User\Preferences\UpdateDashboardView;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardMeController extends Controller
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
            'view' => 'me',
        ]);

        $employee = auth()->user()->getEmployeeObjectForCompany($company);

        return View::component('ShowDashboardMe', [
            'company' => $company,
            'user' => auth()->user()->refresh(),
            'employee' => new EmployeeResource($employee),
            'teams' => TeamResource::collection($employee->teams()->get()),
            'worklogCount' => $employee->worklogs()->count(),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
