<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
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
        $company = Cache::get('currentCompany');
        if ($company->has_dummy_data) {
            (new RemoveDummyData)->execute([
                'company_id' => $company->id,
                'author_id' => auth()->user()->id,
            ]);

            return redirect(tenant('/dashboard'));
        }

        (new GenerateDummyData)->execute([
            'company_id' => $company->id,
            'author_id' => auth()->user()->id,
        ]);

        return redirect(tenant('/dashboard'));
    }
}
