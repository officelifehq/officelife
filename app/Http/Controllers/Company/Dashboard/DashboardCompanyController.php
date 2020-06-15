<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;

class DashboardCompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('ShowCompany', [
        ]);
    }
}
