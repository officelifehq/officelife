<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\User\Preferences\UpdateDashboardView;
use App\Http\Resources\Company\Team\Team as TeamResource;
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

        $employee = auth()->user()->getEmployeeObjectForCompany($company);

        // building the collection containing the days with the worklogs
        $dates = collect([]);
        $monday = Carbon::now()->startOfWeek();
        $dates->push([
            'day' => DateHelper::getLongDayAndMonth($monday->subDays(3)),
            'name' => DateHelper::getLongDayAndMonth($monday->subDays(3)),
        ]);

        for ($i = 0; $i < 5 ; $i++) {
            $currentDate->month = $month;
            $months->push([
                'id' => $month,
                'name' => mb_convert_case($currentDate->format($format), MB_CASE_TITLE, 'UTF-8'),
            ]);
        }

        return View::component('ShowDashboardTeam', [
            'company' => $company,
            'user' => auth()->user()->refresh(),
            'employee' => new EmployeeResource($employee),
            'teams' => TeamResource::collection($employee->teams()->get()),
            'worklogCount' => $employee->worklogs()->count(),
            'notifications' => auth()->user()->notifications->where('read', false)->take(5),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }
}
