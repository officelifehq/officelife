<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\DestroyCompanyNews;
use App\Services\Company\Adminland\EmployeeStatus\UpdateEmployeeStatus;
use App\Http\Resources\Company\CompanyNews\CompanyNews as CompanyNewsResource;

class AdminCompanyNewsController extends Controller
{
    /**
     * Show the list of company news.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $news = CompanyNewsResource::collection(
            $company->news()->orderBy('created_at', 'desc')->get()
        );

        return Inertia::render('Adminland/CompanyNews/Index', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'news' => $news,
        ]);
    }

    /**
     * Show the Create news view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Adminland/CompanyNews/Create', [
            'notifications' => Auth::user()->getLatestNotifications($company),
        ]);
    }

    /**
     * Create the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new CreateCompanyNews)->execute($request);

        return response()->json([
            'data' => $news,
        ]);
    }

    /**
     * Update the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $employeeStatusId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_status_id' => $employeeStatusId,
            'name' => $request->get('name'),
        ];

        $employeeStatus = (new UpdateEmployeeStatus)->execute($request);

        return new EmployeeStatusResource($employeeStatus);
    }

    /**
     * Delete the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $companyNewsId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $companyNewsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'company_news_id' => $companyNewsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyCompanyNews)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
