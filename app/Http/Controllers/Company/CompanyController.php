<?php

namespace App\Http\Controllers\Company;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function index()
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
    public function create()
    {
        return Inertia::render('Home/CreateCompany');
    }

    /**
     * Create the company.
     *
     * @param Request $request
     *
     * @return RedirectResponse
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
