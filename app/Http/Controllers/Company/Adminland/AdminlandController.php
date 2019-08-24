<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminlandController extends Controller
{
    /**
     * Show the account dashboard.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $numberEmployees = $company->employees()->count();

        return Inertia::render('Adminland/Index', [
            'numberEmployees' => $numberEmployees,
            'notifications' => Auth::user()->getLatestNotifications($company),
        ]);
    }
}
