<?php

namespace App\Http\Controllers\Company;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Dashboard/MyCompany', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'ownerPermissionLevel' => config('villagers.authorizations.administrator'),
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
            'author_id' => Auth::user()->id,
            'name' => $request->get('name'),
        ]);

        return Redirect::route('dashboard', $company->id);
    }
}
