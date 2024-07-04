<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Jobs\JobsViewHelper;
use Inertia\Inertia;
use Inertia\Response;

class JobsController extends Controller
{
    /**
     * Display the index of the jobs page.
     */
    public function index(): Response
    {
        $companiesCollection = JobsViewHelper::index();

        return Inertia::render('Jobs/Index', [
            'companies' => $companiesCollection,
        ]);
    }
}
