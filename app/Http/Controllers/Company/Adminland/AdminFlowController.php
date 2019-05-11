<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Flow\Flow as FlowResource;

class AdminFlowController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('currentCompany');
        $flows = FlowResource::collection(
            $company->flows()->orderBy('name', 'asc')->get()
        );

        return View::component('ShowAccountFlows', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'flows' => $flows,
        ]);
    }

    /**
     * Show the Create flow view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Cache::get('currentCompany');

        return View::component('CreateAccountFlow', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
        ]);
    }
}
