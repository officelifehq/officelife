<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Jobs\UpdateDashboardPreference;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardMeController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('cachedCompanyObject');
        $employee = Cache::get('cachedEmployeeObject');

        UpdateDashboardPreference::dispatch([
            'user_id' => auth()->user()->id,
            'company_id' => $company->id,
            'view' => 'me',
        ]);

        return Inertia::render('Dashboard/Me', [
            'company' => $company,
            'employee' => new EmployeeResource($employee),
            'teams' => TeamResource::collection($employee->teams()->get()),
            'worklogCount' => $employee->worklogs()->count(),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
