<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class AdminlandController extends Controller
{
    /**
     * Show the account dashboard.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $companyId)
    {
        $company = Cache::get('cachedCompanyObject');
        $numberEmployees = $company->employees()->count();

        return Inertia::render('Adminland/Index', [
            'company' => $company,
            'numberEmployees' => $numberEmployees,
            'employee' => new EmployeeResource(auth()->user()->getEmployeeObjectForCompany($company)),
            'notifications' => auth()->user()->getLatestNotifications($company),
        ]);
    }
}
