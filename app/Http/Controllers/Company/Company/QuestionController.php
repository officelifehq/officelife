<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class QuestionController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('ShowCompany', [
            'company' => $company,
            'user' => Auth::user()->refresh(),
            'employee' => new EmployeeResource(Auth::user()->getEmployeeObjectForCompany($company)),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ownerPermissionLevel' => config('officelife.permission_level.administrator'),
        ]);
    }
}
