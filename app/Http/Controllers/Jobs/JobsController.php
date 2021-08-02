<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsIndexViewHelper;

class JobsController extends Controller
{
    /**
     * Display the index of the jobs page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $companiesCollection = JobsIndexViewHelper::index();

        return Inertia::render('Jobs/Index', [
            'companies' => $companiesCollection,
        ]);
    }

    /**
     * Display the details of all the job openings in a given company.
     *
     * @return Response
     */
    public function show(Request $request, int $companyUuid): Response
    {
        $companiesCollection = JobsIndexViewHelper::index();

        return Inertia::render('Jobs/Index', [
            'companies' => $companiesCollection,
        ]);
    }
}
