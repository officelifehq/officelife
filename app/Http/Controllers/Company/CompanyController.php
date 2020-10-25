<?php

namespace App\Http\Controllers\Company;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\Company\Adminland\Company\CreateCompany;

class CompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard/MyCompany', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ownerPermissionLevel' => config('officelife.permission_level.administrator'),
        ]);
    }

    /**
     * Show the create company form.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Home/CreateCompany');
    }

    /**
     * Create the company.
     *
     * @param Request $request
     *
     * @return \Illuminate\Routing\Redirector|RedirectResponse
     */
    public function store(Request $request)
    {
        $company = (new CreateCompany)->execute([
            'author_id' => Auth::user()->id,
            'name' => $request->input('name'),
        ]);

        return redirect($company->id.'/welcome');
    }
}
