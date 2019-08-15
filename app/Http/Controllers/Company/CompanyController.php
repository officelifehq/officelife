<?php

namespace App\Http\Controllers\Company;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Services\Company\Adminland\Company\CreateCompany;

class CompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('cachedCompanyObject');

        return Inertia::render('Dashboard/MyCompany', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('homas.authorizations.administrator'),
        ]);
    }

    /**
     * Show the create company form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Home/CreateCompany');
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

        return Redirect::route('dashboard', $company->id);
    }
}
