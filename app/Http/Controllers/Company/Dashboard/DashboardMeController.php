<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UpdateDashboardPreference;
use App\Http\Resources\Company\Team\Team as TeamResource;

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

        return Inertia::render('Dashboard/Me', [
            'teams' => TeamResource::collection($employee->teams()->get()),
            'worklogCount' => $employee->worklogs()->count(),
            'notifications' => Auth::user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
