<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
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
            $company->flows()->orderBy('title', 'asc')->get()
        );

        return View::component('ShowAccountFlows', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'flows' => $flows,
        ]);
    }
}
