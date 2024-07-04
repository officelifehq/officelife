<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Services\Company\Adminland\Company\CreateCompany;
use App\Services\Company\Adminland\Company\JoinCompany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    /**
     * Show the create company form.
     */
    public function create(): Response
    {
        return Inertia::render('Home/CreateCompany');
    }

    /**
     * Create the company.
     *
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
     * @return Response
     */
    public function join(Request $request)
    {
        return Inertia::render('Home/JoinCompany');
    }

    /**
     * Join the company.
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
