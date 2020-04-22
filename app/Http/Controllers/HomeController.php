<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @return Response
     */
    public function index()
    {
        Cache::forget('cachedCompanyObject_'.Auth::user()->id);
        Cache::forget('cachedEmployeeObject_'.Auth::user()->id);

        $employees = Auth::user()->employees()->with('company')->get();
        $companiesCollection = collect([]);

        foreach ($employees as $employee) {
            $companiesCollection->push([
                'company_name' => $employee->company->name,
                'company_id' => $employee->company_id,
                'number_of_employees' => $employee->company->employees()->count(),
                'joined_at' => $employee->created_at,
            ]);
        }

        return Inertia::render('Home/Index', [
            'employees' => $companiesCollection,
        ]);
    }
}
