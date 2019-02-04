<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Services\Company\Company\CreateCompany;
use App\Services\Company\Company\RemoveDummyData;
use App\Services\Company\Company\GenerateDummyData;

class CompanyController extends Controller
{
    /**
     * Company details.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $company = Company::findOrFail($request->route()->parameter('company'));
        $numberEmployees = $company->employees()->count();

        return view('company.dashboard')
            ->withNumberEmployees($numberEmployees)
            ->withCompany($company);
    }

    /**
     * Show the create company form.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        return view('company.company.create');
    }

    /**
     * Create the company.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = (new CreateCompany)->execute([
            'author_id' => auth()->user()->id,
            'name' => $request->get('name'),
        ]);

        return redirect($company->id.'/dashboard');
    }

    /**
     * Generate or remove fake data for the Company.
     *
     * @return \Illuminate\Http\Response
     */
    public function dummy()
    {
        if (auth()->user()->Company->has_dummy_data) {
            (new RemoveDummyData)->execute([
                'Company_id' => auth()->user()->Company_id,
                'author_id' => auth()->user()->id,
            ]);

            return redirect(tenant('/dashboard'));
        }

        (new GenerateDummyData)->execute([
            'Company_id' => auth()->user()->Company_id,
            'author_id' => auth()->user()->id,
        ]);

        return redirect(tenant('/dashboard'));
    }
}
