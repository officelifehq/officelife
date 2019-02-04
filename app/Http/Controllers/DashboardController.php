<?php

namespace App\Http\Controllers;

use App\Models\Company\Company;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = auth()->user()->employees()->get();

        return view('dashboard.index')
            ->withEmployees($employees);
    }
}
