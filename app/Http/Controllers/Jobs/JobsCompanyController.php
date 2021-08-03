<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsCompanyViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobsCompanyController extends Controller
{
    /**
     * Shows all the jobs openings in the given company.
     *
     * @param Request $request
     * @param string $slug
     * @return mixed
     */
    public function index(Request $request, string $slug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = JobsCompanyViewHelper::index($company);

        return Inertia::render('Jobs/Company/Index', [
            'data' => $data,
        ]);
    }

    /**
     * Shows the details of the job opening.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @return mixed
     */
    public function show(Request $request, string $slug, string $jobOpeningSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        if ($request->query('ignore') == false) {
            $opening->increment('page_views');
        }

        $data = JobsCompanyViewHelper::show($company, $opening);

        return Inertia::render('Jobs/Company/Show', [
            'data' => $data,
        ]);
    }
}
