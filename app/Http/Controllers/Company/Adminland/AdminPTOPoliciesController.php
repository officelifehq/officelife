<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Adminland\CompanyPTOPolicy\UpdateCompanyPTOPolicy;
use App\Http\Resources\Company\CompanyPTOPolicy\CompanyPTOPolicy as CompanyPTOPolicyResource;

class AdminPTOPoliciesController extends Controller
{
    /**
     * Show the list of company news.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $policies = CompanyPTOPolicyResource::collection(
            $company->ptoPolicies()->orderBy('year', 'asc')->get()
        );

        return Inertia::render('Adminland/CompanyPTOPolicy/Index', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'ptoPolicies' => $policies,
        ]);
    }

    /**
     * Update the pto policy.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $ptoPolicyId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $ptoPolicyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'company_pto_policy_id' => $ptoPolicyId,
            'total_worked_days' => $request->get('total_worked_days'),
        ];

        $policy = (new UpdateCompanyPTOPolicy)->execute($request);

        return new CompanyPTOPolicyResource($policy);
    }
}
