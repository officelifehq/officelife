<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

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
        $company = Company::findOrFail($companyId);
        $numberEmployees = $company->employees()->count();

        return View::component('ShowAccount', [
            'company' => $company,
            'numberEmployees' => $numberEmployees,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
'notifications' => auth()->user()->notifications->where('read', false)->take(5),
        ]);
    }
}
