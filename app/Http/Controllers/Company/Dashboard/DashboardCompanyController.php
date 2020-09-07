<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;

class DashboardCompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('ShowCompany', [
        ]);
    }
}
