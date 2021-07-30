<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

class JobsController extends Controller
{
    /**
     * Display the index of the jobs page.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Jobs/Index', [
        ]);
    }
}
