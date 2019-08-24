<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Adminland\Company\RemoveDummyData;
use App\Services\Company\Adminland\Company\GenerateDummyData;

class DummyController extends Controller
{
    /**
     * Generate or remove fake data for the Company.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        if ($company->has_dummy_data) {
            (new RemoveDummyData)->execute([
                'company_id' => $company->id,
                'author_id' => Auth::user()->id,
            ]);

            return redirect(tenant('/dashboard'));
        }

        (new GenerateDummyData)->execute([
            'company_id' => $company->id,
            'author_id' => Auth::user()->id,
        ]);

        return redirect(tenant('/dashboard'));
    }
}
