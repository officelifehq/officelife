<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsViewHelper;

class JobsController extends Controller
{
    /**
     * Display the index of the jobs page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $companiesCollection = JobsViewHelper::index();

        return Inertia::render('Jobs/Index', [
            'companies' => $companiesCollection,
        ]);
    }
}
