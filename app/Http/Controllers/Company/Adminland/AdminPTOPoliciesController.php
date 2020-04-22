<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\CompanyPTOPolicy;
use App\Http\Collections\CompanyPTOPolicyCollection;
use App\Services\Company\Adminland\CompanyPTOPolicy\UpdateCompanyPTOPolicy;

class AdminPTOPoliciesController extends Controller
{
    /**
     * Show the list of company news.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $policies = $company->ptoPolicies()->orderBy('year', 'asc')->get();

        $policiesCollection = CompanyPTOPolicyCollection::prepare($policies);

        return Inertia::render('Adminland/CompanyPTOPolicy/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ptoPolicies' => $policiesCollection,
        ]);
    }

    /**
     * Update the pto policy.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $ptoPolicyId
     *
     * @return Response
     */
    public function update(Request $request, int $companyId, int $ptoPolicyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'company_pto_policy_id' => $ptoPolicyId,
            'total_worked_days' => $request->get('total_worked_days'),
            'days_to_toggle' => $request->get('days_to_toggle'),
            'default_amount_of_allowed_holidays' => $request->get('default_amount_of_allowed_holidays'),
            'default_amount_of_sick_days' => $request->get('default_amount_of_sick_days'),
            'default_amount_of_pto_days' => $request->get('default_amount_of_pto_days'),
        ];

        $policy = (new UpdateCompanyPTOPolicy)->execute($request);

        return response()->json([
            'data' => $policy->toObject(),
        ], 200);
    }

    /**
     * Get the holidays for a given PTO policy.
     *
     * @param int $companyId
     * @param int $companyPTOPolicyId
     *
     * @return array
     */
    public function getHolidays(int $companyId, int $companyPTOPolicyId)
    {
        $ptoPolicy = CompanyPTOPolicy::find($companyPTOPolicyId);

        return DateHelper::prepareCalendar($ptoPolicy);
    }
}
