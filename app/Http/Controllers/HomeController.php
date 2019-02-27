<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Display the user home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = auth()->user()->employees()->with('company')->get();
        $companiesCollection = collect([]);

        foreach ($employees as $employee) {
            $companiesCollection->push([
                'company_name' => $employee->company->name,
                'company_id' => $employee->company->id,
                'number_of_employees' => $employee->company->employees()->count(),
                'joined_at' => $employee->created_at,
            ]);
        }

        return View::component('Home', ['employees' => $companiesCollection]);
    }
}
