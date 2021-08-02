<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsIndexViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobsCompanyController extends Controller
{
    /**
     * Shows all the jobs openings in the given company.
     *
     * @return Response
     */
    public function index(Request $request, int $slug): Response
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs.company.index');
        }

        $data = JobsIndexViewHelper::show($company);

        return Inertia::render('Jobs/Company/Index', [
            'data' => $data,
        ]);
    }
}
