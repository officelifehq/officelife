<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobsCompanyController extends Controller
{
    /**
     * Shows all the jobs openings in the given company.
     *
     * @return mixed
     */
    public function index(Request $request, string $slug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs.company.index');
        }

        $data = JobsViewHelper::index($company);

        return Inertia::render('Jobs/Company/Index', [
            'data' => $data,
        ]);
    }
}
