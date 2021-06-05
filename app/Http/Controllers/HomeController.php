<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index()
    {
        return $this->companies(true);
    }

    /**
     * Display the list of companies.
     *
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function list()
    {
        return $this->companies(false);
    }

    /**
     * Display the list of companies.
     *
     * @param bool $redirect
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function companies(bool $redirect)
    {
        Cache::forget('cachedCompanyObject_'.Auth::user()->id);
        Cache::forget('cachedEmployeeObject_'.Auth::user()->id);

        $employees = Auth::user()->employees()->with('company')->notLocked()->get();

        if ($redirect && $employees->count() === 1) {
            return redirect()->route('welcome', ['company' => $employees->first()->company_id]);
        }

        $companiesCollection = $employees->map(function ($employee) {
            return [
                'company_name' => $employee->company->name,
                'company_id' => $employee->company_id,
                'number_of_employees' => $employee->company->employees()->count(),
                'joined_at' => $employee->created_at,
            ];
        });

        return Inertia::render('Home/Index', [
            'employees' => $companiesCollection,
        ]);
    }
}
