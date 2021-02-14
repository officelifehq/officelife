<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
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
     * @return Response
     */
    public function index(): Response
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
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $ptoPolicyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'company_pto_policy_id' => $ptoPolicyId,
            'total_worked_days' => $request->input('total_worked_days'),
            'days_to_toggle' => $request->input('days_to_toggle'),
            'default_amount_of_allowed_holidays' => $request->input('default_amount_of_allowed_holidays'),
            'default_amount_of_sick_days' => $request->input('default_amount_of_sick_days'),
            'default_amount_of_pto_days' => $request->input('default_amount_of_pto_days'),
        ];

        $policy = (new UpdateCompanyPTOPolicy)->execute($data);

        return response()->json([
            'data' => $policy->toObject(),
        ], 200);
    }

    /**
     * Get the holidays for a given PTO policy.
     *
     * @param int $companyId
     * @param int $companyPTOPolicyId
     * @return array
     */
    public function getHolidays(int $companyId, int $companyPTOPolicyId): array
    {
        $ptoPolicy = CompanyPTOPolicy::find($companyPTOPolicyId);

        return DateHelper::prepareCalendar($ptoPolicy);
    }
}
