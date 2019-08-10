<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\Company\CreateCompany;

class CompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('currentCompany');

        return View::component('ShowCompany', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }

    /**
     * Show the create company form.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $company = Cache::get('currentCompany');

        return View::component('CreateCompany', [
            'company' => $company,
            'notifications' => auth()->user()->getLatestNotifications($company),
        ]);
    }

    /**
     * Create the company.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = (new CreateCompany)->execute([
            'author_id' => auth()->user()->id,
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'company_id' => $company->id,
        ]);
    }
}
