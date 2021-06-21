<?php

namespace App\Http\Controllers\Company;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\Company\Adminland\Company\JoinCompany;
use App\Services\Company\Adminland\Company\CreateCompany;

class CompanyController extends Controller
{
    /**
     * Show the create company form.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Home/CreateCompany');
    }

    /**
     * Create the company.
     *
     * @param Request $request
     *
     * @return \Illuminate\Routing\Redirector|RedirectResponse
     */
    public function store(Request $request)
    {
        $company = (new CreateCompany)->execute([
            'author_id' => Auth::user()->id,
            'name' => $request->input('name'),
        ]);

        return redirect($company->id.'/welcome');
    }

    /**
     * Show the Join company screen.
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|RedirectResponse
     */
    public function join(Request $request)
    {
        return Inertia::render('Home/JoinCompany');
    }

    /**
     * Join the company.
     *
     * @param Request $request
     */
    public function actuallyJoin(Request $request)
    {
        $company = (new JoinCompany)->execute([
            'user_id' => Auth::user()->id,
            'code' => $request->input('code'),
        ]);

        return response()->json([
            'data' => [
                'url' => route('dashboard', [
                    'company' => $company->id,
                ]),
            ],
        ], 201);
    }
}
